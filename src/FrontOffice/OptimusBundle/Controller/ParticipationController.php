<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FrontOffice\OptimusBundle\Entity\Participation;
use FrontOffice\OptimusBundle\Entity\HistoryEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FrontOffice\OptimusBundle\Event\HistoryEventEvent;
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Participation controller.
 *
 * @Route("/")
 */
class ParticipationController extends Controller {

    /**
     * 
     *
     * @Route("event={id}/participer", name="add_participation", options={"expose"=true})
     * 
     * @Method("GET|POST")
     */
    public function addParticipationAction($id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        
        $newparticipation = new Participation();
        $newparticipation->setParticipant($user);
        $newparticipation->setEvent($event);

        $em->persist($newparticipation);
        $em->flush();
        $action = 'participation';
        $eventhistory = new HistoryEventEvent($user, $event, $action);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_EVENT_REGISTER, $eventhistory);
        return $this->redirect($this->generateUrl('show_event', array('id' => $id)));
        //return $response = new Response();
    }
   
   
    
   

    /**
     * Deletes a Club entity.
     *
     * @Route("/event={id}/quitter", name="exit_event", options={"expose"=true})
     * 
     */
    public function exitEventAction($id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('FrontOfficeOptimusBundle:Event')->find($id);
        $p = $em->getRepository('FrontOfficeOptimusBundle:Participation')->findBy(array('event' => $event, 'participant' => $user));
        if($p != null){
        $em->remove($p[0]);
        $em->flush();
         $etat = 0 ;
         $msg = "Participer";
         $msgmap="Participer";
        }  else {
              $newparticipation = new Participation();
        $newparticipation->setParticipant($user);
        $newparticipation->setEvent($event);

        $em->persist($newparticipation);
        $em->flush();
         $action = 'participation';
        $eventhistory = new HistoryEventEvent($user, $event, $action);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(FrontOfficeOptimusEvent::AFTER_EVENT_REGISTER, $eventhistory);
        $etat = 1 ;
        $msg = "Annuler participation";
        $msgmap ="Annuler";
        }
        
        $response = new Response();
        $response->setContent(json_encode(array('msg' => $msg ,'etat' => $etat,'msgmap'=>$msgmap )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
