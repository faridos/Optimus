<?php

namespace FrontOffice\OptimusBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use FrontOffice\UserBundle\Entity\User;
use FrontOffice\OptimusBundle\Entity\Club;

class NotificationClubEvent extends Event {
    protected $club;
    protected $user;
    protected $action;

    public function __construct(User $user, Club $club,$action) {
        $this->club = $club;
        $this->user = $user;
        $this->action= $action;
    }
    public function getClub() {
        return $this->club;
    }

    public function getUser() {
        return $this->user;
    }

    public function getAction() {
        return $this->action;
    }

    //put your code here
}
