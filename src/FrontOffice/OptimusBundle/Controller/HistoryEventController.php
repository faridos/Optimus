<?php

namespace FrontOffice\OptimusBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FrontOffice\OptimusBundle\Entity\HistoryEvent;

/**
 * HistoryEvent controller.
 *
 * @Route("/")
 */
class HistoryEventController extends Controller {
 
  /**
     * 
     *
     * @Route("profil={id}/historique/evenement", name="history_event", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function showAction(){
  $em = $this->getDoctrine()->getEntityManager();
  $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
  $historys=$em->getRepository('FrontOfficeOptimusBundle:HistoryEvent')->findBy(array('user'=>$user),array('date'=>'DESC'));
  
   return $this->render('FrontOfficeUserBundle:Profile:historyEvent.html.twig', array('historys_event'=>$historys));
 }
 
 /**
     * 
     *
     * @Route("historyEvent/load/{last_id}", name="history_event_ajax", options={"expose"=true})
     * @Method("GET|POST")
     * 
     */
    public function getHistoryEventLoadAjax($last_id) {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $historyevent = new ArrayCollection();
        $historyevent = $em->getRepository('FrontOfficeOptimusBundle:HistoryEvent')->historyEventLoadAjax($user->getId(), $last_id);
        if (!$historyevent) {
            throw $this->createNotFoundException('Unable to find Event entity.');
        }

        $response = new Response();
        $tabhistoryevents = json_encode($historyevent);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($tabhistoryevents);
        return $response;
    }
 
}
