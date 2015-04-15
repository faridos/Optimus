<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participcompetition")
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\ParticipCompetitionRepository")
 */
class ParticipCompetition {
    
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
     * @ORM\ManyToOne(targetEntity="FrontOffice\OptimusBundle\Entity\Competition", inversedBy="particips")
     */
    private $competition;

    /**
     *
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="particips")
     * @ORM\JoinColumn(nullable=false ,onDelete="CASCADE")
     */
    private $participant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_paticipation", type="datetime")
     */
    private $datePaticipation;
    
     
    
    public function __construct() {
        $this->datePaticipation = new \Datetime();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set datePaticipation
     *
     * @param \DateTime $datePaticipation
     * @return ParticipCompetition
     */
    public function setDatePaticipation($datePaticipation)
    {
        $this->datePaticipation = $datePaticipation;
    
        return $this;
    }

    /**
     * Get datePaticipation
     *
     * @return \DateTime 
     */
    public function getDatePaticipation()
    {
        return $this->datePaticipation;
    }

    /**
     * Set competition
     *
     * @param \FrontOffice\OptimusBundle\Entity\Competition $competition
     * @return ParticipCompetition
     */
    public function setCompetition(\FrontOffice\OptimusBundle\Entity\Competition $competition = null)
    {
        $this->competition = $competition;
    
        return $this;
    }

    /**
     * Get competition
     *
     * @return \FrontOffice\OptimusBundle\Entity\Competition 
     */
    public function getCompetition()
    {
        return $this->competition;
    }

    /**
     * Set participant
     *
     * @param \FrontOffice\UserBundle\Entity\User $participant
     * @return ParticipCompetition
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
}