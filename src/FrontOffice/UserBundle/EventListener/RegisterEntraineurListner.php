<?php

namespace FrontOffice\UserBundle\EventListener;

use FrontOffice\UserBundle\Event\UserRegisterEvent;
use FrontOffice\OptimusBundle\Entity\Notification;
use \DateTime;

class RegisterEntraineurListner {

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function onCreateUserNotification(UserRegisterEvent $event) {

        $em = $this->em;

        $notification = new Notification();
        $notification->setEntraineur($event->getUser());
        $notification->setDatenotification(new DateTime());
        $notification->setType('add');
        $notification->setNotificateur($event->getUser()->getId());
        $em->persist($notification);
        $em->flush();
     
    }

    //put your code here
}
