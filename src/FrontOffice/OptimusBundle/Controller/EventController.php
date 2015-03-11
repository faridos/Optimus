<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FrontOffice\OptimusBundle\Entity\Event;
use FrontOffice\OptimusBundle\Form\EventType;
use FrontOffice\OptimusBundle\Entity\Participation;
use FrontOffice\OptimusBundle\Entity\HistoryEvent;
use FrontOffice\OptimusBundle\Event\HistoryEventEvent;
use FrontOffice\OptimusBundle\Event\ParticipationEvent;
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
        $participants = $em->getRepository("FrontOfficeOptimusBundle:Event")->getParticipants($event);
        $isparticipant= false;
        foreach($participants as $participateur){
        if($participateur == $user){
            $isparticipant = true;
        }  
        }
        
        if ($event->getActive() == false) {
            throw $this->createNotFoundException('Event Annulé.');
        }
        return $this->render('FrontOfficeOptimusBundle:Event:participants.html.twig', array(
            'event' => $event,
            'participants' => $participants,
            'isparticipant' => $isparticipant));
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
        $participants = $em->getRepository("FrontOfficeOptimusBundle:Event")->getParticipants($event);
        if ($event->getActive() == false) {
            throw $this->createNotFoundException('Event Annulé.');
        }
        return $this->render('FrontOfficeOptimusBundle:Event:photo.html.twig', array('event' => $event,'participants' => $participants));
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
        $participants = $em->getRepository("FrontOfficeOptimusBundle:Event")->getParticipants($event);
        if ($event->getActive() == false) {
            throw $this->createNotFoundException('Event Annulé.');
        }
        return $this->render('FrontOfficeOptimusBundle:Event:video.html.twig', array('event' => $event,'participants' => $participants));
    }

    /**
     * 
     *
     * @Route("/ajouter", name="add-event")
     * @Method("GET|POST")
     * @Template()
     */
    public function addAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $event = new Event;
        $event->setCreateur($user);
        $event->setDateModification(null);
        $event->setActive(true);
        $form = $this->createForm(new EventType, $event);
        $req = $this->get('request');
        if ($req->getMethod() == "POST") {
            $form->bind($req);
            if ($form->isValid()) {
                $em->persist($event);
                $em->flush();
//add notification + add prticipation + add History
                $action = 'add';
                $eventhistory = new HistoryEventEvent($user, $event, $action);
                $eventparticipation = new ParticipationEvent($user, $event);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_EVENT_REGISTER, $eventhistory);
                $dispatcher->dispatch(FrontOfficeOptimusEvent::PARICIAPTION_REGISTER, $eventparticipation);
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
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        if (!$event || $event->getActive() == 0) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }
        $editForm = $this->createForm(new EventType(), $event);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $event->setDateModification(new DateTime());
            $em->flush();
//add notification
            $action = 'update';
            $eventhistory = new HistoryEventEvent($user, $event, $action);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_EVENT_REGISTER, $eventhistory);

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
     * @Route("={id}/supprimer", name="delete-event")
     * @Method("GET|POST|DELETE")
     * @Template()
     */
    public function deleteAction($id) {
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        if (!$entity || $entity->getActive() == 0) {
            throw $this->createNotFoundException('Unable to find Club entity.');
        }
        $entity->setActive(false);
        $em->persist($entity);
        $em->flush();
        $action = 'delete';
        $eventhistory = new HistoryEventEvent($user, $entity, $action);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_EVENT_REGISTER, $eventhistory);
        return $this->redirect($this->generateUrl('events'));
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

}
