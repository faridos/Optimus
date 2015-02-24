<?php

namespace FrontOffice\OptimusBundle\EventListener;

use FrontOffice\OptimusBundle\Event\HistoryClubEvent;
use FrontOffice\OptimusBundle\Entity\Participation;
use FrontOffice\OptimusBundle\Entity\Notification;
use FrontOffice\OptimusBundle\Entity\HistoryClub;
use \DateTime;

class ClubListner {

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function onCreateNotificationClub(HistoryClubEvent $event) {

        $em = $this->em;

        $notification = new Notification();
        $notification->setClub($event->getClub());
        $notification->setDatenotification(new DateTime());
        $notification->setType('add');
        $notification->setNotificateur($event->getUser()->getId());
        $em->persist($notification);
        $em->flush();
    }

    public function onCreateHistoryClub(HistoryClubEvent $event) {

        $em = $this->em;
        $history = new HistoryClub();
        $history->setAction("Ajout");
        $history->setClub($event->getClub());
        $history->setUser($event->getUser());
        $em->persist($history);
        $em->flush();
        die('ee');
    }

    //put your code here
}
