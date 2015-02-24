<?php

namespace FrontOffice\OptimusBundle\EventListener;

use FrontOffice\OptimusBundle\Event\HistoryEventEvent;
use FrontOffice\OptimusBundle\Entity\HistoryEvent;
use FrontOffice\OptimusBundle\Entity\Participation;
use FrontOffice\OptimusBundle\Entity\Notification;
use \DateTime;

class HistoryListener {

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function onCreateHistoryEvent(HistoryEventEvent $event) {

        $em = $this->em;

        $history = new HistoryEvent();

        $history->setAction("Ajout");

        $history->setEvent($event->getEvent());

        $history->setUser($event->getUser());

        $em->persist($history);
        $em->flush();
    }

    public function onCreateParticipationEvent(HistoryEventEvent $event) {

        $em = $this->em;

        $participation = new Participation();
        $participation->setEvent($event->getEvent());
        $participation->setParticipant($event->getUser());
        $participation->setDatePaticipation(new DateTime());
        $em->persist($participation);
        $em->flush();
    }

    public function onCreateNotificationEvent(HistoryEventEvent $event) {

        $em = $this->em;

        $notification = new Notification();
        $notification->setEvent($event->getEvent());
        $notification->setDatenotification(new DateTime());
        $notification->setType('add');
        $notification->setNotificateur($event->getUser()->getId());
        $em->persist($notification);
        $em->flush();
        die('ee');
    }

    //put your code here
}
