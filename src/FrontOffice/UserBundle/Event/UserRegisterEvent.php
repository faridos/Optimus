<?php

namespace FrontOffice\UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use FrontOffice\UserBundle\Entity\User;

class UserRegisterEvent extends Event {

    protected $user;

    public function __construct(User $user) {

        $this->user = $user;
    }

    public function getUser() {
        return $this->user;
    }

    //put your code here
}
