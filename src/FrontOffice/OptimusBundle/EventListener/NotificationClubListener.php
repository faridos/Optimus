<?php

namespace FrontOffice\OptimusBundle\EventListener;

use FrontOffice\OptimusBundle\Event\NotificationClubEvent;

use FrontOffice\OptimusBundle\Entity\Notification;

use \DateTime;

class NotificationClubListener {

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function onCreateNotificationClub(NotificationClubEvent $event) {

        $em = $this->em;

        $notification = new Notification();
        $notification->setClub($event->getClub());
        $notification->setDatenotification(new DateTime());
        $notification->setType($event->getAction());
        $notification->setNotificateur($event->getUser()->getId());
        $em->persist($notification);
        $em->flush();
        
    }

    

    //put your code here
}
