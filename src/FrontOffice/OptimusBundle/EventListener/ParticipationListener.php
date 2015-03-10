<?php

namespace FrontOffice\OptimusBundle\EventListener;

use FrontOffice\OptimusBundle\Event\ParticipationEvent;

use FrontOffice\OptimusBundle\Entity\Participation;

use \DateTime;

class ParticipationListener {

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function onCreateParticipationEvent(ParticipationEvent $event) {

        $em = $this->em;

        $participation = new Participation();
        $participation->setEvent($event->getEvent());
        $participation->setParticipant($event->getUser());
        $participation->setDatePaticipation(new DateTime());
        $em->persist($participation);
        $em->flush();
        
    }

   
    //put your code here
}
