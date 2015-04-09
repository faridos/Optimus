<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FrontOffice\OptimusBundle\Entity\Event;
use FrontOffice\OptimusBundle\Entity\Notification;
use FrontOffice\OptimusBundle\Entity\Message;
use FrontOffice\OptimusBundle\Form\EventType;
use FrontOffice\OptimusBundle\Form\EventPhotoType;
use FrontOffice\OptimusBundle\Entity\Participation;
use FrontOffice\OptimusBundle\Entity\HistoryEvent;
use FrontOffice\OptimusBundle\Event\HistoryEventEvent;
use FrontOffice\OptimusBundle\Event\ParticipationEvent;
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Event\NotificationSeenEvent;
use FrontOffice\OptimusBundle\Form\UpdateEventType;


use \DateTime;

/**
 * Event controller.
 *
 * @Route("/evenement")
 */
class EventController extends Controller {
    
    

    /**
     * 
     *
     * @Route("", name="events")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository("FrontOfficeOptimusBundle:Event")->findBy(array('active' => 1));
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
//$x=$em->getRepository("FrontOfficeOptimusBundle:Event")->get_distance_m(48.856667,2.350987, 45.767299, 4.834329);
        return $this->render('FrontOfficeOptimusBundle:Event:index.html.twig', array('events' => $events));
    }
    
    
    


    /**
     * 
     *
     * @Route("={id}", name="show_event", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function participantsAction($id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $event = $em->getRepository("FrontOfficeOptimusBundle:Event")->find($id);

        if (!$event || $event->getActive() == false) {
            return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
//        $notification = $em->getRepository('FrontOfficeOptimusBundle:Notification')->findOneBy(array('event' => $event));
//        if ($notification) {
//            $notificationSeen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findOneBy(array('users' => $user, 'notifications' => $notification));
//            if (empty($notificationSeen)) {
//
//                $notifevent = new NotificationSeenEvent($user, $notification);
//                $dispatcher = $this->get('event_dispatcher');
//                $dispatcher->dispatch(FrontOfficeOptimusEvent::NOTIFICATION_SEEN_USER, $notifevent);
//            }
//        }

        $nbr1 = $event->getNbrvu();
        $nbr = $nbr1 + 1 ;
        $event->setNbrvu($nbr);
        $em->merge($event);
        $em->flush();
        return $this->render('FrontOfficeOptimusBundle:Event:showEvent.html.twig', array('user'=> $user,
            'event' => $event,
            
            ));
    }
    
    /**
     * 
     *
     * @Route("={id}/photos", name="photos_event", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function photoAction($id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("FrontOfficeOptimusBundle:Event")->find($id);
        $photos = $em->getRepository("FrontOfficeOptimusBundle:Photo")->findby(array('event' =>$event));
        if ($event->getActive() == false) {
             return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        return $this->render('FrontOfficeOptimusBundle:Event:photo.html.twig', array('event' => $event,'photos' => $photos));
    }
    
    /**
     * 
     *
     * @Route("={id}/videos", name="videos_event", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function videoAction($id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository("FrontOfficeOptimusBundle:Event")->find($id);
       
        if ($event->getActive() == false) {
             return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        return $this->render('FrontOfficeOptimusBundle:Event:video.html.twig', array('event' => $event));
    }

    /**
     * 
     *
     * @Route("/ajouter", name="add-event")
     * @Method("GET|POST")
     * @Template()
     */
    public function addAction(Request $request) {
         if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $event = new Event;
        $event->setCreateur($user);
        $event->setDateModification(null);
        $event->setActive(true);
        $event->setNbrvu(0);
        $form = $this->createForm(new EventType, $event);
        $req = $this->get('request');
        if ($req->getMethod() == "POST") {
            $form->bind($req);
            if ($form->isValid()) {
                $em->persist($event);
                $em->flush();
                
        $notif = new Notification();
        $notif->setNotificateur($user);
        $notif->setType('add');
        $notif->setEvent($event);
        $em->persist($notif);
        $em->flush();
//add notification + add prticipation + add History
               // $action = 'add';
               // $eventhistory = new HistoryEventEvent($user, $event, $action);
                $eventparticipation = new ParticipationEvent($user, $event);
               $dispatcher = $this->get('event_dispatcher');
               // $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_EVENT_REGISTER, $eventhistory);
              $dispatcher->dispatch(FrontOfficeOptimusEvent::PARICIAPTION_REGISTER, $eventparticipation);
                $request->getSession()->getFlashBag()->add('AjouterEvent', "Votre évènement a été ajouter avec success.");
                return $this->redirect($this->generateUrl('show_event', array('id' => $event->getId())));
            }
        }
        return $this->render('FrontOfficeOptimusBundle:Event:new.html.twig', array('form' => $form->createView(), 'user' => $user));
    }

    /**
     * 
     *
     * @Route("={id}/modifier", name="update-event")
     * @Method("GET|POST")
     * @Template()
     */
    public function updateAction(Request $request, $id) {
         if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        if (!$event || $event->getActive() == 0) {
             return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        $editForm = $this->createForm(new UpdateEventType(), $event);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $event->setDateModification(new DateTime());
            $em->flush();
            
        $notif = new Notification();
        $notif->setNotificateur($user);
        $notif->setType('update');
        $notif->setEvent($event);
        $em->persist($notif);
        $em->flush(); 
//add notification
//            $action = 'update';
//            $eventhistory = new HistoryEventEvent($user, $event, $action);
//            $dispatcher = $this->get('event_dispatcher');
//            $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_EVENT_REGISTER, $eventhistory);
            $request->getSession()->getFlashBag()->add('EditEvent', "Votre évènement a été modifié avec success.");
            return $this->redirect($this->generateUrl('show_event', array('id' => $event->getId())));
        }
        return $this->render('FrontOfficeOptimusBundle:Event:edit.html.twig', array(
                    'user' => $user,
                    'event' => $event,
                    'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * 
     *
     * @Route("={id}/supprimer", name="delete-event", options={"expose"=true})
     * @Method("GET|POST|DELETE")
     * @Template()
     */
    public function deleteAction(Request $request,$id) {
         if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        if (!$entity || $entity->getActive() == 0) {
             return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        $entity->setActive(false);
        $em->persist($entity);
        $em->flush();
        
        $notif = new Notification();
        $notif->setNotificateur($user);
        $notif->setType('delete');
        $notif->setEvent($entity);
        $em->persist($notif);
        $em->flush();
        
//        $action = 'delete';
//        $eventhistory = new HistoryEventEvent($user, $entity, $action);
//        $dispatcher = $this->get('event_dispatcher');
//        $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_EVENT_REGISTER, $eventhistory);
        $request->getSession()->getFlashBag()->add('SupprissionEvenement', "Evenement  a été supprimer.");
        return  new Response($id);
    }
    
    
    /**
     * 
     *
     * @Route("{id}/supprimer/event", name="delete-event-profil", options={"expose"=true})
     * @Method("GET|POST|DELETE")
     * @Template()
     */
    public function deleteEventAction(Request $request,$id) {
         if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        if (!$entity || $entity->getActive() == 0) {
             return $this->render('FrontOfficeOptimusBundle::404.html.twig');
        }
        $entity->setActive(false);
        $em->persist($entity);
        $em->flush();
        
        $notif = new Notification();
        $notif->setNotificateur($user);
        $notif->setType('delete');
        $notif->setEvent($entity);
        $em->persist($notif);
        $em->flush();
        
//        $action = 'delete';
//        $eventhistory = new HistoryEventEvent($user, $entity, $action);
//        $dispatcher = $this->get('event_dispatcher');
//        $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_EVENT_REGISTER, $eventhistory);
        
        return $this->redirect($this->generateUrl('accueil'));
    }
    
    /**
     * 
     *
     * @Route("/{id}/paramétres/photo", name="setting_event_photo", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function editPhotoEventAction(Request $request, $id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        
        
            $editForm = $this->createForm(new EventPhotoType(), $event);
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $em->flush();
                return $this->redirect($this->generateUrl('show_event', array('id' => $id)));
            }
            return $this->render('FrontOfficeOptimusBundle:Event:editPhotoEvent.html.twig', array('event' => $event, 'form' => $editForm->createView()));
        
    }
    
    /**
     * 
     *
     * @Route("/{id}/marquerEvent", name="marquerEvent", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function marquerEventAction($id) {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        $user = $this->container->get('security.context')->getToken()->getUser();
      //  return $this->render('FrontOfficeOptimusBundle:Event:showMarquer.html.twig', array('event'=>$event,'user'=>$user));
         $p = $em->getRepository('FrontOfficeOptimusBundle:Participation')->findBy(array('event' => $event, 'participant' => $user));
        if($p != null){
             $msgmap ="Annuler";
        
        
        }  else {
            $msgmap="Participer";
   
}
          $response = new Response();
        $response->setContent(json_encode(array('msgmap'=>$msgmap )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    /**
     * 
     *
     * @Route("load/{last_id}", name="event_ajax", options={"expose"=true})
     * @Method("GET|POST")
     * 
     */
//    public function getEventLoadAjax($last_id) {
//        $em = $this->getDoctrine()->getEntityManager();
//        $user = $this->container->get('security.context')->getToken()->getUser();
//        $lng = $user->getLng();
//        $lat = $user->getLat();
//        $events = new ArrayCollection();
//        $events = $em->getRepository('FrontOfficeOptimusBundle:Event')->getEventLoadAjax(new DateTime(), $lng, $lat, $last_id);
//        if (!$events) {
//            throw $this->createNotFoundException('Unable to find Event entity.');
//        }
//
//        $response = new Response();
//        $tabevents = json_encode($events);
//        $response->headers->set('Content-Type', 'application/json');
//        $response->setContent($tabevents);
//        return $response;
//    }


    /**
     * 
     *
     * @Route("/inviter/ami", name="inviter_ami_event", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function inviterAmiAction() {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');
       
     //  $name = Array();
     
            $name = $request->get("name");
            
            $id =  $request->get("event");
            $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
            $sender = $event->getCreateur();
          
            foreach ($name as $friend) {
                 $message = new Message();
                $namefriend = $em->getRepository('FrontOfficeUserBundle:User')->find($friend);
                $content= 'Bonjour '. $namefriend->getNom() .' '. $namefriend->getPrenom() .' je inviter amon event '. $event->getTitre();
              
                $message->setReciever($friend);
                $message->setSender($sender);
                $message->setMsgTime(new \DateTime());
                $message->setContent($content);
                $message->setEvent($event->getId());
                $em->persist($message);
                $em->flush();
                 
            }
            return new Response('1'); 
        
       
    }

   
    
    /**
     * 
     *
     * @Route("/test2", name="test2", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function SetTime2Action() {
        $var="test";
       
       return new Response($var); 
    }
    
    
    /**
     * 
     *
     * @Route("/testdrr", name="notif_test", options={"expose"=true})
     * 
     */
    public function MessageNotifAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $messages = new ArrayCollection();
               $request = $this->get('request');
        
       $lastid =  $request->get("lastid");
     
        $messages = $em->getRepository('FrontOfficeOptimusBundle:Message')->getNotif($user->getId() , $lastid);
       
        $response = new Response();
        $tabcomments = json_encode($messages);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($tabcomments);
        return $response;
    }
//     /**
//     * 
//     *
//     * @Route("/testdrrir", name="notif_testt", options={"expose"=true})
//     * 
//     */
//    public function MessagecountAction() {
//        $em = $this->getDoctrine()->getManager();
//        $user = $this->container->get('security.context')->getToken()->getUser();
//       $nbr = $em->getRepository('FrontOfficeOptimusBundle:Message')->getcount($user->getId());
//    
//        $response = new Response($nbr[0]['nbrmsg']);
//          return $response;
//    }
    
    /**
     * 
     *
     * @Route("/message_menu", name="message_menu", options={"expose"=true})
     * 
     */
    public function MessageMenuAction() {
        $em = $this->getDoctrine()->getManager();
             $request = $this->get('request');
        
       $lastid =  $request->get("lastid");
     
        $user = $this->container->get('security.context')->getToken()->getUser();
       $messages = $em->getRepository('FrontOfficeOptimusBundle:Message')->findBy(array('reciever'=>$user->getId()),array('msgTime'=>'DESC'));
          $i= 0 ;
          $vu = 0 ;
          if($messages !=null){
           $i = $messages[0]->getId() ;
          }
       $nvmessages = $em->getRepository('FrontOfficeOptimusBundle:Message')->getnvmsg($user->getId() ,$lastid );
       $nvnonmessages = $em->getRepository('FrontOfficeOptimusBundle:Message')->findBy(array('reciever'=>$user->getId() , 'vu'=> $vu ));
          return $this->render('FrontOfficeOptimusBundle:Message:MessageMenu.html.twig', array('messages' => $messages , 'nvmessages'=>$nvmessages ,'lastid' =>$i  , 'nvnonmessages'=>$nvnonmessages));
    }
}

