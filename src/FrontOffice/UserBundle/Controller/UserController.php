<?php

namespace FrontOffice\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FrontOffice\OptimusBundle\Controller\MessageController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FrontOffice\UserBundle\Entity\User;
use FrontOffice\OptimusBundle\Entity\Palmares;
use FrontOffice\OptimusBundle\Entity\Notification;
use FrontOffice\OptimusBundle\Entity\NotificationSeen;
use FrontOffice\UserBundle\Form\UserType;
use FrontOffice\UserBundle\Form\UserPhotoType;
use FrontOffice\UserBundle\Form\UserNameType;
use FrontOffice\UserBundle\Form\UserEmailType;
use Symfony\Component\Validator\Constraints\DateTime;
use FrontOffice\OptimusBundle\Event\NotificationSeenEvent;
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * User controller.
 *
 * @Route("/")
 */
class UserController extends Controller {

    /**
     * 
     *
     * @Route("", name="index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {

        return $this->render('FrontOfficeUserBundle:User:redirect.html.twig');
    }

    /**
     * 
     *
     * @Route("", name="accueil")
     * @Method("GET")
     * @Template()
     */
    public function accueilAction() {
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository("FrontOfficeOptimusBundle:Event")->findBy(array('active' => 1));

        return $this->render('FrontOfficeUserBundle:User:accueil.html.twig', array('user' => $user, 'events' => $events));
    }

    /**
     * 
     *
     * @Route("profil={id}", name="show_profil")
     * @Method("GET")
     * @Template()
     */
    public function getProfileUserAction($id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user1 = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        $notification = $em->getRepository('FrontOfficeOptimusBundle:Notification')->findOneBy(array('entraineur' => $user));
        if ($notification) {
            $notificationSeen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findOneBy(array('users' => $user1, 'notifications' => $notification));
            if (empty($notificationSeen)) {
           
                $notifevent = new NotificationSeenEvent($user, $notification);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::NOTIFICATION_SEEN_USER, $notifevent);
            }
        }
        return $this->render('FrontOfficeUserBundle:Profile:show.html.twig', array('user' => $user, 'user1' => $user1));
    }

    /**
     * 
     *
     * @Route("profil={id}/inviter", name="add_relation")
     * @Method("GET|POST")
     * @Template()
     */
    public function addFriendAction($id) {
        $em = $this->getDoctrine()->getManager();
        $user1 = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('FrontOfficeUserBundle:User')->find($id);
        $userManager = $this->container->get('fos_user.user_manager');
        $user2 = $userManager->findUserByUsername($user->getUsername());
        $relation = $this->container->get('sly_relation');
        $relation->relationShip('friendship', $user1, $user2);
        if (false === $relation->exists()) {
            $relation->create();
        }
        return $this->redirect($this->generateUrl('show_profil', array('id' => $id)));
    }

    /**
     * 
     *
     * @Route("profil={id}/accepter", name="accept_relation")
     * @Method("GET|POST")
     * @Template()
     */
    public function acceptAction($id) {
        $user1 = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $Invitations = $em->getRepository('SlyRelationBundle:Relation')->findOneBy(array('object1Id' => $id, 'object2Id' => $user1->getId()));
        $Invitations->setConfirmed(true);
        $em->persist($Invitations);
        $em->flush();
        return $this->redirect($this->generateUrl('accueil'));
    }

    /**
     * 
     *
     * @Route("profil={id}/retirer", name="delete_relation")
     * @Method("GET|POST")
     * @Template()
     */
    public function deleteFriendAction($id) {
        $em = $this->getDoctrine()->getManager();
        $user1 = $this->container->get('security.context')->getToken()->getUser();
        $user = $em->getRepository('FrontOfficeUserBundle:User')->find($id);
        $userManager = $this->container->get('fos_user.user_manager');
        $user2 = $userManager->findUserByUsername($user->getUsername());
        $relationLeft = $em->getRepository('SlyRelationBundle:Relation')->findOneBy(array('object1Id' => $user1->getId(), 'object2Id' => $user2->getId()));
        $relationRight = $em->getRepository('SlyRelationBundle:Relation')->findOneBy(array('object1Id' => $user2->getId(), 'object2Id' => $user1->getId()));
        if ($relationLeft) {
            $em->remove($relationLeft);
            $em->flush();
        }
        if ($relationRight) {
            $em->remove($relationRight);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('show_profil', array('id' => $id)));
    }

}
