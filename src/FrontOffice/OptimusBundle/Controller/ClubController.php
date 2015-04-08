<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Entity\Club;
use FrontOffice\OptimusBundle\Form\ClubType;
use FrontOffice\OptimusBundle\Form\UpdateClubType;
use FrontOffice\OptimusBundle\Form\ClubPhotoType;
use FrontOffice\OptimusBundle\Entity\Member;
use FrontOffice\OptimusBundle\Entity\Message;
use FrontOffice\OptimusBundle\Entity\Comment;
use FrontOffice\OptimusBundle\Entity\Palmares;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FrontOffice\OptimusBundle\Form\MemberType;
use FrontOffice\OptimusBundle\Controller\MemberController;
use \DateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use FrontOffice\OptimusBundle\Event\HistoryClubEvent;
use FrontOffice\OptimusBundle\Event\NotificationClubEvent;
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;
use FrontOffice\OptimusBundle\Event\NotificationSeenEvent;

/**
 * Club controller.
 *
 * @Route("/")
 */
class ClubController extends Controller {

    /**
     * 
     *
     * @Route("club/ajouter", name="add_club")

     * @Template("FrontOfficeOptimusBundle:Club:add.html.twig")
     */
    public function addClubAction(Request $request) {
          if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        if (!$this->get('security.context')->isGranted('ROLE_ENTRAINEUR')) {
            // Sinon on déclenche une exception « Accès interdit »
             return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();

        $club = new Club();
        $club->setCreateur($user);
        $club->setActive(1);
        $club->setIsPayant(0);
        $club->setLienWeb('http://www.sport.com');
        $form = $this->createForm(new ClubType(), $club);
        $req = $this->get('request');
        if ($req->getMethod() == 'POST') {
            $form->bind($req);

            $em->persist($club);
            $em->flush();
            $action = 'add';
            $clubevent = new HistoryClubEvent($user, $club, $action);
//            $clubnotification = new NotificationClubEvent($user, $club, $action);
//            $dispatcher = $this->get('event_dispatcher');
//            $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
//            $dispatcher->dispatch(FrontOfficeOptimusEvent::NOTIFICATION_CLUB, $clubnotification);
             $request->getSession()->getFlashBag()->add('AjoutClub', "Club  a été creé avec success.");
            return $this->redirect($this->generateUrl('show_club', array('id' => $club->getId())));
        }
        return array(
            'form' => $form->createView(),
            'user' => $user);
    }

    /**
     * 
     *
     * @Route("club={id}", name="show_club", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function showClubAction($id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        if (!$club || $club->getActive() == 0 || ($club->getIsPayant()== 0 && $club->getCreateur() != $user)) {
            return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        $notification = $em->getRepository('FrontOfficeOptimusBundle:Notification')->findOneBy(array('club' => $club));
        if ($notification) {
            $notificationSeen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findOneBy(array('users' => $user, 'notifications' => $notification));
            if (empty($notificationSeen)) {

                $notifevent = new NotificationSeenEvent($user, $notification);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::NOTIFICATION_SEEN_USER, $notifevent);
            }
        }
       $progarammes = $em->getRepository('FrontOfficeOptimusBundle:Program')->findBy(array('clubp' => $club), array('datedebut' => 'desc'));
      $nbv1 = $club->getNbrvu();
        $nb = $nbv1 + 1 ;
        $club->setNbrvu($nb);
        $em->merge($club);
        $em->flush();
        return $this->render('FrontOfficeOptimusBundle:Club:showClub.html.twig', array('club' => $club, 'user' => $user, 'programmes' =>$progarammes) );
    }

    /**
     * Edits an existing Club entity.
     *
     * @Route("club={id}/modifier", name="club_update")

     * @Template("FrontOfficeOptimusBundle:Club:edit.html.twig")
     */
    public function editClubAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        if (!$club || $club->getActive() == 0) {
             return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        if ($club->getCreateur() == $user) {
            $editForm = $this->createForm(new UpdateClubType(), $club);
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $em->flush();
                // add History 
                $action = 'update';
                $clubevent = new HistoryClubEvent($user, $club, $action);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
                 $request->getSession()->getFlashBag()->add('ModificationClub', "Club  a été Modifier.");
                return $this->redirect($this->generateUrl('show_profil', array('id' => $user->getId())));
            }
            return array('club' => $club,'form' => $editForm->createView(),'user' => $user);
        }
    }

    /**
     * Deletes a Club entity.
     *
     * @Route("/club={id}/supprimer", name="club_delete",  options={"expose"=true})
     * 
     */
    public function deleteAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        if (!$entity || $entity->getActive() == 0) {
            return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        if ($entity->getCreateur() == $user) {
            $entity->setActive(false);
            $em->persist($entity);
            $em->flush();
            // add History 
            $action = 'delete';
            $clubevent = new HistoryClubEvent($user, $entity, $action);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
             $request->getSession()->getFlashBag()->add('SupprissionClub', "Club  a été supprimer.");

            return  new Response($id);
        }
    }
    /**
     * Deletes a Club entity.
     *
     * @Route("/club={id}/delete", name="club_supprimer",  options={"expose"=true})
     * 
     */
    public function deleteclubAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        if (!$entity || $entity->getActive() == 0) {
            return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        if ($entity->getCreateur() == $user) {
            $entity->setActive(false);
            $em->persist($entity);
            $em->flush();
            // add History 
            $action = 'delete';
            $clubevent = new HistoryClubEvent($user, $entity, $action);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
             $request->getSession()->getFlashBag()->add('SupprissionClub', "Club  a été supprimer.");

             return $this->redirect($this->generateUrl('show_profil', array('id' => $user->getId())));
        }
    }

    /**
     * 
     *
     * @Route("club={id}/palmares", name="palmares_club", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getPalmaresUserAction($id) {
        $em = $this->getDoctrine()->getManager();

        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $rewards = $em->getRepository('FrontOfficeOptimusBundle:Reward')->findBy(array('club' => $club));
        return $this->render('FrontOfficeOptimusBundle:Club:palmaresClub.html.twig', array('rewards' => $rewards, 'club' => $club));
    }

    /**
     * 
     *
     * @Route("club={id}/albums", name="albums_club", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getAlbumsClubAction($id) {
         if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $albums = $em->getRepository('FrontOfficeOptimusBundle:Album')->findBy(array('club' => $club));
        return $this->render('FrontOfficeOptimusBundle:Club:albumsClub.html.twig', array('albums' => $albums, 'club' => $club));
    }

    /**
     * 
     *
     * @Route("club={id}/album={id_album}/photos", name="photos_club", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function getPhotosUserAction($id_album) {
         if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('FrontOfficeOptimusBundle:Album')->find($id_album);
        $club = $album->getClub();
        $photos = $em->getRepository('FrontOfficeOptimusBundle:Photo')->findby(array('album' => $album));
        return $this->render('FrontOfficeOptimusBundle:Club:photosClub.html.twig', array('album' => $album, 'club' => $club, 'photos' => $photos));
    }

    /**
     * Creates a new Club entity.
     *
     * @Route("club/{id}/request", name="request_club", options={"expose"=true})
     * @Method("POST|GET")
     */
    public function requestAction(Request $request, $id) {
         if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
        $adherent = $em->getRepository('FrontOfficeOptimusBundle:Member')->findBy(array('member' => $user, 'clubad' => $club));
        if (empty($adherent)) {
            $member = new Member();
            $member->setClubad($club);
            $member->setMember($this->container->get('security.context')->getToken()->getUser());
            $em->persist($member);
            $em->flush();
            $action = 'rejoindre';
            $clubevent = new HistoryClubEvent($user, $club, $action);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
            $response = new Response();
            $memberJson = json_encode($member);
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent($memberJson);
            return $response;
        }
    }

    /**
     * Deletes a Club entity.
     *
     * @Route("/club={id}/quitter", name="exit_club", options={"expose"=true})
     * 
     */
    public function exitClubAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        $member = $em->getRepository('FrontOfficeOptimusBundle:Member')->findOneBy(array('member' => $user, 'clubad' => $club));
        if (!empty($member)) {
            $em->remove($member);
            $em->flush();
        }
        $action = 'quitter';
        $clubevent = new HistoryClubEvent($user, $club, $action);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_CLUB_REGISTER, $clubevent);
        $response = new Response();
        $memberJson = json_encode($member);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($memberJson);
        return $response;
    }

    /**
     * 
     *
     * @Route("demande={id}/accept", name="accept_demande", options={"expose"=true})
     * @Method("GET|POST")
     */
    public function confirmAction(Request $request, $id) {
         if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $demande = $em->getRepository('FrontOfficeOptimusBundle:Member')->find($id);
        if (!$demande) {
            return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        $demande->setConfirmed(1);
        if ($demande->getConfirmed() == 1) {
            $date = new DateTime();
            $demande->setDateconfirm($date);
            $em->persist($demande);
            $em->flush();
           return $this->render('FrontOfficeOptimusBundle:Club:return.html.twig', array('demande' => $demande ));
        }
    }

    /**
     * 
     *
     * @Route("club={id}/paramétres/photo", name="setting_club_photo", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function editPhotoAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('FrontOfficeOptimusBundle:Club')->find($id);
        if (!$club || $club->getActive() == 1) {
            $editForm = $this->createForm(new ClubPhotoType(), $club);
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $em->flush();
                return $this->redirect($this->generateUrl('show_club', array('id' => $id)));
            }
            return $this->render('FrontOfficeOptimusBundle:Club:editPhotoClub.html.twig', array('club' => $club, 'form' => $editForm->createView()));
//                    'user' => $user,)
        } else {
             return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        
     
    }
    /**
     * 
     *
     * @Route("club/inviter/ami", name="inviter_ami_club", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
       
     public function inviterClubAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
       
     //  $name = Array();
     
            $name = $request->get("name");
            
            $id =  $request->get("club");
            $club = $em->getRepository('FrontOfficeOptimusBundle:club')->find($id);
            $sender = $club->getCreateur();
          
            foreach ($name as $friend) {
                 $message = new Message();
                $namefriend = $em->getRepository('FrontOfficeUserBundle:User')->find($friend);
                $content= 'Bonjour '. $namefriend->getNom() .' '. $namefriend->getPrenom() .' je inviter amon Club '. $club->getNom();
              
                $message->setReciever($friend);
                $message->setSender($sender);
                $message->setMsgTime(new \DateTime());
                $message->setContent($content);
                $message->setClub($club->getId());
                $em->persist($message);
                $em->flush();
                 
            }
            return new Response('1'); 
        
       
    }

}
