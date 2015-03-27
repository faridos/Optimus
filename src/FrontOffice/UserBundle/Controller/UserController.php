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
class UserController extends Controller
{

    /**
     *
     *
     * @Route("", name="index_optumis")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {


        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository("FrontOfficeOptimusBundle:Event")->findAll(array('active' => 1));
        $eventsMap = $em->getRepository("FrontOfficeOptimusBundle:Event")->getEventsMap();

        return $this->render('FrontOfficeUserBundle:User:redirect.html.twig', array('events' => $eventsMap));
    }

    /**
     *
     *
     * @Route("/", name="accueil")
     * @Method("GET")
     * @Template()
     */
    public function accueilAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository("FrontOfficeOptimusBundle:Event")->findBy(array('active' => 1), array('dateDebut' => 'desc'));
        $eventsMap = $em->getRepository("FrontOfficeOptimusBundle:Event")->getEventsMap();
        $frinds = $em->getRepository("FrontOfficeUserBundle:User")->getFrinds($user->getId());
        $listevents = array();
        $k = 0;
         foreach ($events as $value) {
            $i = 0;


            //test si le user connecté est le créateur
            foreach ($user->getEvenements() as $evenuser) {

                if ($evenuser->getId() == $value->getId()) {
                    $i = 1;
                    $listevents[$k] = $value->getId();
                    //  $listevents['datecreation'][$k] = $value->getDateCreation();
                    $k = $k + 1;
                }
            }
            //test si le user connecté est participe dans ce evenement
            if ($i == 0) {
                foreach ($user->getParticipations() as $participation) {
                    if ($participation->getEvent()->getId() == $value->getId()) {
                        $i = 1;
                        $listevents[$k] = $value->getId();
                        // $listevents['datecreation'][$k] = $value->getDateCreation();
                        $k = $k + 1;
                    }
                }

            }

            if ($i == 0) {
                foreach ($frinds as $ami) {
                    //test si l'un des amis de user  connecté est le créateur
                    foreach ($ami->getEvenements() as $amieven) {
                        if ($i == 0) {
                            if ($amieven->getId() == $value->getId()) {
                                $i = 1;
                                $listevents[$k] = $value->getId();
                                //      $listevents['datecreation'][$k] = $value->getDateCreation();
                                $k = $k + 1;
                            }
                        }
                    }

                    //test si l'un des amis De user connecté est participe dans ce evenement
                    if ($i == 0) {
                        foreach ($ami->getParticipations() as $participationami) {
                            if ($participationami->getEvent()->getId() == $value->getId()) {
                                $i = 1;
                                $listevents[$k] = $value->getId();
                                //       $listevents['datecreation'][$k] = $value->getDateCreation();
                                $k = $k + 1;
                            }
                        }

                    }


                }

            }

        }
        $c = 0;

        $eventss = array();
        foreach ($listevents as  $ev) {


            $id = $ev;
            $eventss[$c] = $em->getRepository("FrontOfficeOptimusBundle:Event")->find($id);
            $c = $c + 1;

        }

              return $this->render('FrontOfficeUserBundle:User:accueil.html.twig', array('user' => $user, 'events' => $eventss, 'eventsMap' => $eventsMap));
    }

    /**
     *
     *
     * @Route("{id}/profil", name="show_profil", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getProfileUserAction($id)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user1 = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        $participation = $em->getRepository('FrontOfficeOptimusBundle:Participation')->findBy(array('participant' => $user), array('datePaticipation' => 'desc'));
        $notification = $em->getRepository('FrontOfficeOptimusBundle:Notification')->findOneBy(array('entraineur' => $user));
        if ($notification) {
            $notificationSeen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findOneBy(array('users' => $user1, 'notifications' => $notification));
            if (empty($notificationSeen)) {

                $notifevent = new NotificationSeenEvent($user1, $notification);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::NOTIFICATION_SEEN_USER, $notifevent);
            }
        }

        return $this->render('FrontOfficeUserBundle:Profile:show.html.twig', array('user' => $user, 'user1' => $user1, 'participations' => $participation));
    }

    /**
     *
     *
     * @Route("profil={id}/inviter", name="add_relation", options={"expose"=true})
     * @Method("GET|POST")
     *
     */
    public function addFriendAction($id)
    {
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
    public function clubsMemberAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user1 = $this->container->get('security.context')->getToken()->getUser();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        if (!is_object($user)) {
            return $this->render('FrontOfficeOptimusBundle::404.html.twig');
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
    public function acceptAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Invitation = $em->getRepository('SlyRelationBundle:Relation')->find($id);
        $Invitation->setConfirmed(true);
        $em->persist($Invitation);
        $em->flush();
        $response = new Response($id);
        return $response;
    }

    /**
     *
     *
     * @Route("profil={id}/accepter", name="accept_invitation_profil", options={"expose"=true})
     * @Method("GET|POST")
     *
     */
    public function acceptprofilAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user1 = $this->container->get('security.context')->getToken()->getUser();
        $Invitation = $em->getRepository('SlyRelationBundle:Relation')->findOneBy(array('object1Id' => $id, 'object2Id' => $user1->getId()));
        $Invitation->setConfirmed(true);
        $em->persist($Invitation);
        $em->flush();
        $response = new Response($Invitation->getId());
        return $response;
    }

    /**
     *
     *
     * @Route("invitation={id}/supprimer", name="supprimer_invitation", options={"expose"=true})
     * @Method("GET|POST")
     *
     */
    public function deleteInvitationAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $Invitation = $em->getRepository('SlyRelationBundle:Relation')->find($id);
        $em->remove($Invitation);
        $em->flush();
        $response = new Response($id);
        return $response;
    }

    /**
     *
     *
     * @Route("profil={id}/retirer", name="delete_relation")
     * @Method("GET|POST")
     * @Template()
     */
    public function deleteFriendAction($id)
    {
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
    public function getClubUserAction($id)
    {
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
    public function getAlbumsUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        $albums = $em->getRepository('FrontOfficeOptimusBundle:Album')->findBy(array('user' => $user));
        return $this->render('FrontOfficeUserBundle:Profile:albums_user.html.twig', array('albums' => $albums, 'user' => $user));
    }

    /**
     *
     *
     * @Route("profil={id}/album={id_album}/photos", name="photos_user", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getPhotosUserAction($id, $id_album)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        $album = $em->getRepository('FrontOfficeOptimusBundle:Album')->find($id_album);
        $photos = $em->getRepository('FrontOfficeOptimusBundle:Photo')->findby(array('album' => $album));
        return $this->render('FrontOfficeUserBundle:Profile:photos_user.html.twig', array('user' => $user, 'album' => $album, 'photos' => $photos));
    }

    /**
     *
     *
     * @Route("profil={id}/palmares", name="palmares_user", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getPalmaresUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        $rewards = $em->getRepository('FrontOfficeOptimusBundle:Reward')->findBy(array('user' => $user));
        return $this->render('FrontOfficeUserBundle:Profile:palmares_user.html.twig', array('rewards' => $rewards, 'user' => $user));
    }

    /**
     *
     *
     * @Route("/coordonne={lng}/{lat}", name="user_coordonne", options={"expose"=true})
     * @Method("GET|POST")
     *
     */
    public function CoordonneAction($lng, $lat)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $user->setLng($lng);
        $user->setLat($lat);

        $em->persist($user);
        $em->flush();

        return $response = new Response();
    }

    /**
     *
     *
     * @Route("profil={id}/settings", name="setting_user", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function editAccountAction($id)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('FrontOfficeUserBundle:User')->find($id);
        return $this->render('FrontOfficeUserBundle:Resetting:editAccount.html.twig', array('user' => $user));
    }

    /**
     *
     *
     * @Route("profil={id}/paramétres/photo", name="setting_user_photo", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function editPhotoAction(Request $request, $id)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeUserBundle:User')->find($id);
        $editForm = $this->createForm(new UserPhotoType(), $entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('show_profil', array('id' => $id)));
        }
        return $this->render('FrontOfficeOptimusBundle:Photo:editPhotoProfil.html.twig', array('form' => $editForm->createView()));
    }

    /**
     *
     *
     * @Route("profil={id}/paramétres/username", name="setting_user_username", options={"expose"=true})
     * @Method("POST|GET")
     * @Template()
     */
    public function editUserNameAction(Request $request, $id)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeUserBundle:User')->find($id);
        $editForm = $this->createForm(new UserNameType(), $entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('setting_user', array('id' => $id)));
        }
        return $this->render('FrontOfficeUserBundle:Resetting:editUserName.html.twig', array('form' => $editForm->createView()));
    }

    /**
     *
     *
     * @Route("profil={id}/settings/email", name="setting_user_email", options={"expose"=true})
     * @Method("POST|GET|HEAD")
     * @Template("FrontOfficeUserBundle:Resetting:editEmail.html.twig")
     */
    public function editEmailAction(Request $request, $id)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeUserBundle:User')->find($id);
        $editForm = $this->createForm(new UserEmailType(), $entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('setting_user', array('id' => $id)));
        }
        return array('form' => $editForm->createView());
    }

    /**
     *
     *
     * @Route("profil={id}/settings/notifications", name="setting_user_notifications", options={"expose"=true})
     * @Method("POST|GET|HEAD")
     * @Template("FrontOfficeUserBundle:Resetting:editNotification.html.twig")
     */
    public function editNoificationAction(Request $request, $id)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeUserBundle:User')->find($id);
        if (!$entity) {
            return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }

        $editForm = $this->createForm(new UserType(), $entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('setting_user_notifications', array('id' => $id)));
        }
        return array(
            'entity' => $entity,

            'edit_form' => $editForm->createView(),
        );
    }

    /**
     *
     *
     * @Route("profil={id}/settings/confidentiality", name="setting_user_confidentiality", options={"expose"=true})
     * @Method("POST|GET|HEAD")
     *
     */
    public function editConfidentialityAction($id)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeUserBundle:User')->find($id);

        return $this->render('FrontOfficeUserBundle:Resetting:editConfidentiality.html.twig', array('entity' => $entity));
    }

    /**
     *
     *
     * @Route("profil={id}/notifications", name="all_notifications_user", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getAllNotificationAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $notifications = $em->getRepository('FrontOfficeOptimusBundle:Notification')->getNotification($user->getId(), $user->getCreatedAt());
        return $this->render('FrontOfficeUserBundle:Profile:showAllNotifications.html.twig', array('notifications' => $notifications, 'user' => $user));
    }

    /**
     *
     *
     * @Route("profil={id}/invitations", name="all_invitations_user", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getAllInvitationAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $invitations = $em->getRepository('FrontOfficeUserBundle:User')->getInvitations($user);
        return $this->render('FrontOfficeUserBundle:Profile:showAllInvitations.html.twig', array('invitations' => $invitations, 'user' => $user));
    }

    /**
     *
     *
     * @Route("profil={id}/messages", name="all_messages_user", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getAllMessageAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
//        $conversation = $em->getRepository('FrontOfficeOptimusBundle:Conversation')->getUserConversation($user);
//       if{
        return $this->render('FrontOfficeUserBundle:Profile:showAllMessage.html.twig', array('user' => $user));
    }



    /**
     *
     *
     * @Route("{id}/show/accueil", name="show_accueil")
     * @Method("GET")
     * @Template()
     */
    public function accueilshowAction($id)
    {
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository("FrontOfficeOptimusBundle:Event")->find($id);
        $eventsMap = $em->getRepository("FrontOfficeOptimusBundle:Event")->getEventsMap();
        $frinds = $em->getRepository("FrontOfficeUserBundle:User")->getFrinds($user->getId());



           return $this->render('FrontOfficeUserBundle:User:accueilS.html.twig', array('user' => $user, 'event' => $event, 'eventsMap' => $eventsMap));
    }


}
