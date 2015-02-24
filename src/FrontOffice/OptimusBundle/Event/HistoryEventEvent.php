<?php

namespace FrontOffice\OptimusBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use FrontOffice\UserBundle\Entity\User;

class HistoryEventEvent extends Event {

    protected $event;
    protected $user;

    public function __construct(User $user, \FrontOffice\OptimusBundle\Entity\Event $event) {
        $this->event = $event;
        $this->user = $user;
    }
    public function getEvent() {
        return $this->event;
    }

    public function getUser() {
        return $this->user;
    }

        
}
