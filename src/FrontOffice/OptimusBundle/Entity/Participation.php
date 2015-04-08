<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\ParticipationRepository")
 */
class Participation {
    
     /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="FrontOffice\OptimusBundle\Entity\Event", inversedBy="participations")
     */
    private $event;

    /**
     *
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="participations")
     * @ORM\JoinColumn(nullable=false ,onDelete="CASCADE")
     */
    private $participant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_paticipation", type="datetime")
     */
    private $datePaticipation;
    
     /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Notification", mappedBy="participation", cascade={"persist","remove"})
     */
    protected $notificationParticipation;

    /**
     * Set datePaticipation
     *
     * @param \DateTime $datePaticipation
     * @return Participation
     */
    public function setDatePaticipation($datePaticipation) {
        $this->datePaticipation = $datePaticipation;

        return $this;
    }

    /**
     * Get datePaticipation
     *
     * @return \DateTime 
     */
    public function getDatePaticipation() {
        return $this->datePaticipation;
    }

public function getId() {
        return $this->id;
    }

    /**
     * Set event
     *
     * @param \FrontOffice\OptimusBundle\Entity\Event $event
     * @return Participation
     */
    public function setEvent(\FrontOffice\OptimusBundle\Entity\Event $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \FrontOffice\OptimusBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set participant
     *
     * @param \FrontOffice\UserBundle\Entity\User $participant
     * @return Participation
     */
    public function setParticipant(\FrontOffice\UserBundle\Entity\User $participant)
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * Get participant
     *
     * @return \FrontOffice\UserBundle\Entity\User 
     */
    public function getParticipant()
    {
        return $this->participant;
    }
    
    /**
     * Add notificationParticipation
     *
     * @param \FrontOffice\OptimusBundle\Entity\Notification $notificationParticipation
     * @return Event
     */
    public function addNotificationParticipation(\FrontOffice\OptimusBundle\Entity\Notification $notificationParticipation)
    {
        $this->notificationParticipation[] = $notificationParticipation;
    
        return $this;
    }

    /**
     * Remove notification_event
     *
     * @param \FrontOffice\OptimusBundle\Entity\Notification $notificationParticipation
     */
    public function removeNotificationParticipation(\FrontOffice\OptimusBundle\Entity\Notification $notificationParticipation)
    {
        $this->notificationParticipation->removeElement($notificationParticipation);
    }

    /**
     * Get notificationParticipation
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotificationParticipation()
    {
        return $this->notificationParticipation;
    }
    public function __construct() {
        $this->datePaticipation = new \Datetime();
    }
}