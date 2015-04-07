<?php

namespace FrontOffice\OptimusBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FrontOffice\OptimusBundle\Event\NotificationSeenEvent;
use FrontOffice\OptimusBundle\FrontOfficeOptimusEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * NotificationSeen controller.
 *
 * @Route("/notificationSeen")
 */
class NotificationSeenController extends Controller {

    /**
     * 
     *
     * @Route("={id}/save", name="save_notification", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function newAction($id) {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            // Sinon on déclenche une exception « Accès interdit »
            throw new AccessDeniedException('.');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser(); //utilisateur courant
        $notification = $em->getRepository('FrontOfficeOptimusBundle:Notification')->find($id);
        if ($notification) {
            $notificationSeen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findOneBy(array('users' => $user, 'notifications' => $notification));
//            if (empty($notificationSeen)) {
//                $notifevent = new NotificationSeenEvent($user, $notification);
//                $dispatcher = $this->get('event_dispatcher');
//                $dispatcher->dispatch(FrontOfficeOptimusEvent::NOTIFICATION_SEEN_USER, $notifevent);
//            }
        }
        $response = new Response();
        $NotificationJson = json_encode($notificationSeen);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($NotificationJson);
        return $response;
      
    }
    
    /**
     * 
     *
     * @Route("/notifParticipe", name="notifParticipe_seen", options={"expose"=true})
     * @Method("GET|POST")
     * 
     */
    public function updateNotifSeenParticipeAction() {
        $em = $this->getDoctrine()->getEntityManager();		
        $id = $_POST["id"];       
        $notificationSeen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findOneBy(array('id' => $id));
        
            $notificationSeen->setVu(1);
            $em->persist($notificationSeen);
            $em->flush();
        
        return new Response();
             
        
    }

}
