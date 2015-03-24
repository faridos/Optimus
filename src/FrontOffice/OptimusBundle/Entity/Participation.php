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
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="FrontOffice\OptimusBundle\Entity\Event", inversedBy="participations")
     */
    private $event;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="participations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_paticipation", type="datetime")
     */
    private $datePaticipation;

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
    
    public function __construct() {
        $this->datePaticipation = new \Datetime();
    }
}