<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * NotificationSeen
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\NotificationSeenRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="notificationseen")
 */
class NotificationSeen
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="notificationseen")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
   
    protected $users;
    
    /**
     * @ORM\ManyToOne(targetEntity="Notification", inversedBy="notificationsen")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     **/
   
    protected $notifications;
    
    public function getId() {
        return $this->id;
    }

    public function getUsers() {
        return $this->users;
    }

    public function getNotifications() {
        return $this->notifications;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUsers($users) {
        $this->users = $users;
    }

    public function setNotifications($notifications) {
        $this->notifications = $notifications;
    }


    
}