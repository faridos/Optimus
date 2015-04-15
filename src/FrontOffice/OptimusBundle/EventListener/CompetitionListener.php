<?php

namespace FrontOffice\OptimusBundle\EventListener;

use FrontOffice\OptimusBundle\Event\ParticipationCompetitionEvent;

use FrontOffice\OptimusBundle\Entity\ParticipCompetition;

use \DateTime;

class CompetitionListener {

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function onCreateParticipationCimpetition(ParticipationCompetitionEvent $event) {

        $em = $this->em;

        $participation = new ParticipCompetition();
        $participation->setCompetition($event->getCompetition());
        $participation->setParticipant($event->getMember());
        $participation->setDatePaticipation(new DateTime());
        $em->persist($participation);
        $em->flush();
        
    }

   
    //put your code here
}
