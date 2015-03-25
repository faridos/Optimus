<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Notification
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\NotificationRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="notification")
 */
class Notification {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="notificateur", type="integer")
     */
    private $notificateur;

    /**
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="notification_entraineur")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * */
    protected $entraineur;

    /**
     * @ORM\ManyToOne(targetEntity="Event",inversedBy="notification_event", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event;

    /**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="notification_club")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id")
     * */
    protected $club;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_notification", type="datetime")
     */
    private $datenotification;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\NotificationSeen", mappedBy="notifications", cascade={"persist","remove"})
     * */
    protected $notificationsen;

    public function getId() {
        return $this->id;
    }

    public function getNotificateur() {
        return $this->notificateur;
    }

    public function getNotificationsen() {
        return $this->notificationsen;
    }

    public function setNotificateur($notificateur) {
        $this->notificateur = $notificateur;
    }

    public function setNotificationsen($notificationsen) {
        $this->notificationsen = $notificationsen;
    }

    public function getEntraineur() {
        return $this->entraineur;
    }

    public function getEvent() {
        return $this->event;
    }

    public function getType() {
        return $this->type;
    }

    public function getDatenotification() {
        return $this->datenotification;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setEntraineur($entraineur) {
        $this->entraineur = $entraineur;
    }

    public function setEvent($event) {
        $this->event = $event;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setDatenotification(\DateTime $datenotification) {
        $this->datenotification = $datenotification;
    }

    public function getUsers() {
        return $this->users;
    }
    public function getClub() {
        return $this->club;
    }

    public function setClub($club) {
        $this->club = $club;
    }

    
    public function setUsers($users) {
        $this->users = $users;
    }

    public function getDuréeNotification() {
        $date = new \DateTime();
        $diff = $date->diff($this->datenotification);
        $durée = "";
        if ($diff->d >= 1):
            $durée = "il y'a " . $diff->d . " days";
        elseif ($diff->h >= 1):
            $durée = "il y'a " . $diff->h . " heur";
        elseif ($diff->i > 1):
            $durée = "il y'a " . $diff->i . " min";
        else:
            $durée = "Just Now";
        endif;
        return $durée;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->notificationsen = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add notificationsen
     *
     * @param \FrontOffice\OptimusBundle\Entity\NotificationSeen $notificationsen
     * @return Notification
     */
    public function addNotificationsen(\FrontOffice\OptimusBundle\Entity\NotificationSeen $notificationsen) {
        $this->notificationsen[] = $notificationsen;

        return $this;
    }

    /**
     * Remove notificationsen
     *
     * @param \FrontOffice\OptimusBundle\Entity\NotificationSeen $notificationsen
     */
    public function removeNotificationsen(\FrontOffice\OptimusBundle\Entity\NotificationSeen $notificationsen) {
        $this->notificationsen->removeElement($notificationsen);
    }

}