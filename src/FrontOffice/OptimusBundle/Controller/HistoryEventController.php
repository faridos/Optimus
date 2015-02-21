<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FrontOffice\OptimusBundle\Entity\HistoryEvent;


class HistoryEventController extends Controller {
 
 public function addAction($event,$action){
  
   $em = $this->getDoctrine()->getEntityManager();
   
    $history_event = new HistoryEvent;
    $history_event->setEvent($event);
    $history_event->setAction($action);
    
    $em->persist($history_event);
    $em->flush();

 }
 
 public function showAction(){
  $em = $this->getDoctrine()->getEntityManager();
  $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
  $histories=$em->getRepository('FrontOfficeOptimusBundle:HistoryEvent')->findBy(array('user'=>$user),array('date'=>'DESC'));
  
   return $this->render('FrontOfficeOptimusBundle:HistoryEvent:show.html.twig', array('history_event'=>$histories));
 }
 
 
 
}
