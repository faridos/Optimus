<?php

namespace FrontOffice\OptimusBundle\EventListener;

use FrontOffice\OptimusBundle\Event\HistoryClubEvent;

use FrontOffice\OptimusBundle\Entity\HistoryClub;
use \DateTime;

class ClubListener {

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($em) {
        $this->em = $em;
    }

   
    public function onCreateHistoryClub(HistoryClubEvent $event) {

        $em = $this->em;
        $history = new HistoryClub();
        $history->setAction($event->getAction());
        $history->setClub($event->getClub());
        $history->setUser($event->getUser());
        $em->persist($history);
        $em->flush();
       
    }

    //put your code here
}
