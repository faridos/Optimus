<?php

namespace FrontOffice\OptimusBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use FrontOffice\UserBundle\Entity\User;
use FrontOffice\OptimusBundle\Entity\Club;

class HistoryClubEvent extends Event {
    protected $club;
    protected $user;

    public function __construct(User $user, Club $club) {
        $this->club = $club;
        $this->user = $user;
    }
    public function getClub() {
        return $this->club;
    }

    public function getUser() {
        return $this->user;
    }

        //put your code here
}
