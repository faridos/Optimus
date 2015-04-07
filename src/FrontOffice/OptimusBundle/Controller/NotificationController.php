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
use FrontOffice\UserBundle\Entity\User;


/**
 * Notification controller.
 *
 * @Route("/notification")
 */
class NotificationController extends Controller {

    /**
     * 
     *
     * @Route("/participe", name="participe_notification", options={"expose"=true})
     * @Method("GET|POST")
     * @Template()
     */
    public function participeNotifAction() {
       
        $res=array();
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $amis = $em->getRepository('FrontOfficeUserBundle:User')->getFrinds($user->getId());
        //$notification = $em->getRepository('FrontOfficeOptimusBundle:Notification')->find($id);
       if($user->getConfigNotif()->getEvent()){
           $c=0;
           foreach ($amis as $ami){
             foreach ($ami->getNotificateur() as $notif){
                 if($notif->getDatenotification()> $user->getcreatedAt()){
                     
                     $res[$c]=$notif;
                     $c++;
                    
                     
                 }
           }  
               
           }
       }
        
        
       var_dump($res);die();
        
        $response = new Response();
        $NotificationJson = json_encode($notificationSeen);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($NotificationJson);
        return $response;
      
    }

}
