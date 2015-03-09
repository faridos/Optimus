<?php

namespace FrontOffice\OptimusBundle\EventListener;

use FrontOffice\OptimusBundle\Event\NotificationClubEvent;

use FrontOffice\OptimusBundle\Entity\Notification;
use FrontOffice\OptimusBundle\Entity\Member;
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
    public function onCreateMemberClub(NotificationClubEvent $event) {

        $em = $this->em;

        $member = new Member();
            $member->setClubad($event->getClub());
            $member->setMember($event->getUser());
            $member->setConfirmed('1');
            $date = new DateTime();
            $member->setDateconfirm($date);
            $em->persist($member);
            $em->flush();
        
    }

    

    //put your code here
}
