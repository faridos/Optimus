<?php

namespace FrontOffice\OptimusBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use FrontOffice\UserBundle\Entity\User;
use FrontOffice\OptimusBundle\Entity\Notification;

class NotificationSeenEvent extends Event {
   
    protected $user;
    protected $notification;

    public function __construct(User $user, Notification $notification) {
       
        $this->user = $user;
        $this->notification= $notification;
    }
   
    public function getUser() {
        return $this->user;
    }

    public function getNotification() {
        return $this->notification;
    }

        //put your code here
}
