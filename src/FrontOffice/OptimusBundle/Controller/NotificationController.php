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
use FrontOffice\OptimusBundle\Entity\NotificationSeen;
use Doctrine\Common\Collections\ArrayCollection;

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
       
        $res= array();
        $resjour=array();
        $tab1 = array("participation", "AnnulerParticip", "add", "update", "delete");
        $tab2 = array("comment", "photo");
        //$notifsParticip = new ArrayCollection();
        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $amis = $em->getRepository('FrontOfficeUserBundle:User')->getFrinds($user->getId());
        $eventjours = $em->getRepository('FrontOfficeOptimusBundle:Event')->getEventJour();
        
        foreach ($eventjours as $eventjour){
            foreach ($user->getParticipations() as $participe){
                if($participe->getEvent()->getId() == $eventjour->getId()){
                   $resjour[]= $eventjour;
                }
            }
        }
       
        //$notification = $em->getRepository('FrontOfficeOptimusBundle:Notification')->find($id);
       if($user->getConfigNotif()->getEvent()){
           $c=0;
           foreach ($amis as $ami){
             foreach ($ami->getNotificateur() as $notif){
                 if(in_array($notif->getType(), $tab1)){
                 $i=0;
                 if($notif->getDatenotification()> $user->getcreatedAt()){
                     foreach ($user->getNotificationseen() as $notifSeen){
                         if($notifSeen->getNotifications()->getId()== $notif->getId())
                         {
                             $i=1;
                         }
                     }
                     if($i==0){
                     
                     $res[$c]=$notif;
                     $c++;
                         }
                 }
           }  
             }
               
           }
           foreach ($user->getParticipations() as $participe){
               foreach ($participe->getEvent()->getNotificationEvent() as $notif2){
                   if($user->getId() != $notif2->getNotificateur()->getId() and in_array($notif2->getType(), $tab2) ){
                   $i=0;
                    foreach ($user->getNotificationseen() as $notifSeen){
                         if($notifSeen->getNotifications()->getId() == $notif2->getId())
                         {
                             $i=1;
                         }
                       
                     }
                       if($i==0){
                     
                     $res[$c]=$notif2;
                     $c++;
                         }
                   }
               }
               
           }
        }
      
       foreach ($res as $val){
        $notifseen = new NotificationSeen();
        $notifseen->setUsers($user);
        $notifseen->setNotifications($val);
        $em->persist($notifseen);
        $em->flush();
       }
        $notificationnonvu = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findBy(array("users"=>$user->getId() ,"vu"=>0));
        $datenotificationseen = $em->getRepository('FrontOfficeOptimusBundle:NotificationSeen')->findBy(array("users"=>$user->getId()),array("datenotificationseen"=>'DESC'));
     
         return $this->render('FrontOfficeOptimusBundle:Notification:notifParticipe.html.twig', array('datenotificationseen' => $datenotificationseen, 'count'=>$notificationnonvu,'res'=>$res , 'resjour'=>$resjour ));
        
      
    }

}
