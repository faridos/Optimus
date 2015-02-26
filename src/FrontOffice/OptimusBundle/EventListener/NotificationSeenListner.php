<?php

namespace FrontOffice\OptimusBundle\EventListener;

use FrontOffice\OptimusBundle\Event\NotificationSeenEvent;
use FrontOffice\OptimusBundle\Entity\NotificationSeen;


class NotificationSeenListner {

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function onCreateNotificationSeen(NotificationSeenEvent $event) {

        $em = $this->em;
        $NotificationSeen = new NotificationSeen();
        $NotificationSeen->setNotifications($event->getNotification());
        $NotificationSeen->setUsers($event->getUser());
        $em->persist($NotificationSeen);
        $em->flush();
    }

    //put your code here
}
