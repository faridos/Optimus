<?php

namespace FrontOffice\OptimusBundle\EventListener;

use FrontOffice\OptimusBundle\Event\HistoryEventEvent;
use FrontOffice\OptimusBundle\Entity\HistoryEvent;

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
        die('ee');
    }

    //put your code here
}
