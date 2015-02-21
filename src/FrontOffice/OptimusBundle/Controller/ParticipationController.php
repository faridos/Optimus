<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FrontOffice\OptimusBundle\Entity\Participation;
use FrontOffice\OptimusBundle\Entity\HistoryEvent;

class ParticipationController extends Controller {

 public function addAction($event_id, $route) {
  $user = $this->container->get('security.context')->getToken()->getUser();
  $req = $this->get('request');


  if ($req->getMethod() == "POST") {
   $em = $this->getDoctrine()->getEntityManager();
   $repository = $em->getRepository("FrontOfficeOptimusBundle:Event");
   $event = $repository->find($event_id);

   $test = $em->getRepository('FrontOfficeOptimusBundle:Participation')->findBy(array('event' => $event, 'participant' => $user));
   if ($test != null)
    return new Response("Echec : déja participé !!");

   $participation = new Participation;
   $participation->setParticipant($user);
   $participation->setEvent($event);


   $em->persist($participation);
   $em->flush();

   // add History 
   $history = new HistoryEvent();
   $history->setAction("Participation");
   $history->setEvent($event);
   $history->setUser($user);
   $em->persist($history);
   $em->flush();
   if ($route == 'accueil')
    return $this->redirect($this->generateUrl('user_index'));
   elseif ($route == 'event')
    return $this->redirect($this->generateUrl('show_event_info', array('id' => $event_id)));
  }
  else {
   return new Response("Erreur : participation invalide !!", 404);
  }
 }

 public function deleteAction($event_id, $route) {
  $em = $this->getDoctrine()->getEntityManager();
  $user = $this->container->get('security.context')->getToken()->getUser();
  $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($event_id);
  $p = $em->getRepository('FrontOfficeOptimusBundle:Participation')->findBy(array('event' => $event, 'participant' => $user));
  if (!$p) {
   throw $this->createNotFoundException('Erreur : Participation invalide !');
  }

  $em->remove($p[0]);
  $em->flush();

  if ($route == 'accueil')
   return $this->redirect($this->generateUrl('user_index'));
  elseif ($route == 'event')
   return $this->redirect($this->generateUrl('show_event_info', array('id' => $event_id)));
 }

}
