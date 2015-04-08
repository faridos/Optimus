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
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id" ,onDelete="CASCADE")
     **/
   
    protected $users;
    
    /**
     * @ORM\ManyToOne(targetEntity="Notification", inversedBy="notificationsen")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id" ,onDelete="CASCADE")
     **/
   
    protected $notifications;
    
      /**
     * @var integer
     *
     * @ORM\Column(name="vu", type="integer", nullable=true)
     */
    private $vu;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNotificationseen", type="datetime")
     */
    private $datenotificationseen;
    
        public function __construct() {
		$this->vu = 0;
                $this->datenotificationseen=new \Datetime();
    }
    
    public function getId() {
        return $this->id;
    }

 


    

    /**
     * Set users
     *
     * @param \FrontOffice\UserBundle\Entity\User $users
     * @return NotificationSeen
     */
    public function setUsers(\FrontOffice\UserBundle\Entity\User $users = null)
    {
        $this->users = $users;
    
        return $this;
    }

    /**
     * Get users
     *
     * @return \FrontOffice\UserBundle\Entity\User 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set notifications
     *
     * @param \FrontOffice\OptimusBundle\Entity\Notification $notifications
     * @return NotificationSeen
     */
    public function setNotifications(\FrontOffice\OptimusBundle\Entity\Notification $notifications = null)
    {
        $this->notifications = $notifications;
    
        return $this;
    }

    /**
     * Get notifications
     *
     * @return \FrontOffice\OptimusBundle\Entity\Notification 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
    
     public function getVu() {
        return $this->vu;
    }


    public function setVu($vu) {
        $this->vu = $vu;
    }
    function getDatenotification() {
        return $this->datenotification;
    }

    function setDatenotification(\DateTime $datenotification) {
        $this->datenotification = $datenotification;
    }



    /**
     * Set datenotificationseen
     *
     * @param \DateTime $datenotificationseen
     * @return NotificationSeen
     */
    public function setDatenotificationseen($datenotificationseen)
    {
        $this->datenotificationseen = $datenotificationseen;
    
        return $this;
    }

    /**
     * Get datenotificationseen
     *
     * @return \DateTime 
     */
    public function getDatenotificationseen()
    {
        return $this->datenotificationseen;
    }
}