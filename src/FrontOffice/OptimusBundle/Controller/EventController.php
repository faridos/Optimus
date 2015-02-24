<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FrontOffice\OptimusBundle\Entity\Event;
use FrontOffice\OptimusBundle\Form\EventType;
use FrontOffice\OptimusBundle\Entity\Participation;
use FrontOffice\OptimusBundle\Entity\HistoryEvent;
use FrontOffice\OptimusBundle\Event\HistoryEventEvent;
use FrontOffice\OptimusBundle\Event\ParticipationEventEvent;
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
     * @Route("/ajouter", name="add-event")
     * @Method("GET")
     * @Template()
     */
    public function addAction() {
        $em = $this->getDoctrine()->getManager();
        //$x=$em->getRepository("FrontOfficeOptimusBundle:Event")->get_distance_m(48.856667,2.350987, 45.767299, 4.834329);
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
                $eventhistory = new HistoryEventEvent($user, $event);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_EVENT_REGISTER, $eventhistory);
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
        $entity = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }
        $editForm = $this->createForm(new EventType(), $entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $entity->setDateModification(new DateTime());
            $em->flush();
            //add notification
            return $this->redirect($this->generateUrl('events', array('id' => $id)));
        }
        return $this->render('FrontOfficeOptimusBundle:Event:edit.html.twig', array(
                    'user' => $user,
                    'entity' => $entity,
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
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Club entity.');
        }
        $entity->setActive(false);
        $em->persist($entity);
        $em->flush();
        //add notification delete

        return $this->redirect($this->generateUrl('events'));
    }

}
