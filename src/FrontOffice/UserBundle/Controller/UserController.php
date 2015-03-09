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
 * @Route("")
 */
class UserController extends Controller {

    /**
     * 
     *
     * @Route("", name="index_optumis")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
       
         
        $em = $this->getDoctrine()->getManager();
      
        $events = $em->getRepository("FrontOfficeOptimusBundle:Event")->findAll();
        $eventsMap = $em->getRepository("FrontOfficeOptimusBundle:Event")->getEventsMap(new \Datetime('- 1 months'));

        return $this->render('FrontOfficeUserBundle:User:redirect.html.twig', array('events' => $eventsMap));
    }

    /**
     * 
     *
     * @Route("/", name="accueil")
     * @Method("GET")
     * @Template()
     */
    public function accueilAction() {
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $lng = $user->getLng();
        $lat = $user->getLat();
        $events = $em->getRepository("FrontOfficeOptimusBundle:Event")->getEventLoad(new \Datetime('- 1 months'), $lng, $lat);
        $eventsMap = $em->getRepository("FrontOfficeOptimusBundle:Event")->getEventsMap(new \Datetime('- 1 months'));
        return $this->render('FrontOfficeUserBundle:User:accueil.html.twig', array('user' => $user, 'events' => $events, 'eventsMap' => $eventsMap));
    }

    /**
     * 
     *
     * @Route("profil={id}", name="show_profil", options={"expose"=true})
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
        $participation = $em->getRepository('FrontOfficeOptimusBundle:Participation')->getEventUserParticipant($id,new \Datetime('- 1 months'));
        return $this->render('FrontOfficeUserBundle:Profile:show.html.twig', array('user' => $user, 'user1' => $user1,'participations' => $participation));
    }

    /**
     * 
     *
     * @Route("profil={id}/inviter", name="add_relation", options={"expose"=true})
     * @Method("GET|POST")
     * 
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
        $response = new Response();
        $relationJson = json_encode($relation);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($relationJson);
        return $response;
    }
     /**
     * Lists  Clubs Member.
     *
     * @Route("profil={id}/clubs", name="clubs_member")
     * @Method("GET")
     * @Template("FrontOfficeUserBundle:Profile:clubs_user.html.twig")
     */
    public function clubsMemberAction($id) {
        $em = $this->getDoctrine()->getManager();
        $user1 = $this->container->get('security.context')->getToken()->getUser();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $clubs = $em->getRepository('FrontOfficeOptimusBundle:Club')->getClubsMember($id);
   
       
        return array(
            'clubs' => $clubs,
            'user' => $user,
            'user1' => $user1,
           
        );
    }

    /**
     * 
     *
     * @Route("invitation={id}/accepter", name="accept_relation", options={"expose"=true})
     * @Method("GET|POST")
     * 
     */
    public function acceptAction($id) {
        $user1 = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $Invitation = $em->getRepository('SlyRelationBundle:Relation')->find($id);
        $Invitation->setConfirmed(true);
        $em->persist($Invitation);
        $em->flush();
        $invitations = $em->getRepository('FrontOfficeUserBundle:User')->getInvitations($user1->getId());
        $response = new Response();
        $invitationJson = json_encode($invitations);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($invitationJson);
        return $response;
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

    /**
     * 
     *
     * @Route("profil={id}/clubs", name="club_user", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getClubUserAction($id) {
         $em = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
         return $this->render('FrontOfficeUserBundle:Profile:clubs_user.html.twig', array('user' => $user));
    }
    /**
     * 
     *
     * @Route("profil={id}/albums", name="albums_user", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getAlbumsUserAction($id) {
         $em = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
          $albums = $em->getRepository('FrontOfficeOptimusBundle:Album')->findBy(array('user' => $user));
          return $this->render('FrontOfficeUserBundle:Profile:albums_user.html.twig', array('albums'=> $albums, 'user' => $user));
    }
    /**
     * 
     *
     * @Route("profil={id}/photos", name="photos_user", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getPhotosUserAction($id) {
         $em = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
          
          return $this->render('FrontOfficeUserBundle:Profile:photos_user.html.twig', array('user' => $user));
    }
    /**
     * 
     *
     * @Route("profil={id}/palmares", name="palmares_user", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getPalmaresUserAction($id) {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        $rewards = $em->getRepository('FrontOfficeOptimusBundle:Reward')->findBy(array('user' => $user));
        return $this->render('FrontOfficeUserBundle:Profile:palmares_user.html.twig', array('rewards'=> $rewards, 'user' => $user));
    }
    
    
    /**
     * 
     *
     * @Route("/coordonne={lng}/{lat}", name="user_coordonne", options={"expose"=true})
     * @Method("GET|POST")
     * 
     */
    public function CoordonneAction($lng,$lat){ 
      $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
  
            $user->setLng($lng);
            $user->setLat($lat);
            
            $em->persist($user);
            $em->flush();

                return $response = new Response();    
         
            }

}
