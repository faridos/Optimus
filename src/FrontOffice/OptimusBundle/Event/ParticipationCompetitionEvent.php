<?php

namespace FrontOffice\OptimusBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use FrontOffice\OptimusBundle\Entity\Member;
use FrontOffice\OptimusBundle\Entity\Competition;
 

class ParticipationCompetitionEvent extends Event {

    protected $competition;
    protected $member;

    public function __construct(Member $member, Competition $competition) {
        $this->competition = $competition;
        $this->member = $member;
    }
    function getCompetition() {
        return $this->competition;
    }

    function getMember() {
        return $this->member;
    }



        
}
