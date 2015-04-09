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
