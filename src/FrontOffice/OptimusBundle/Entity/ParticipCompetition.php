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
     * @ORM\ManyToOne(targetEntity="FrontOffice\OptimusBundle\Entity\Member", inversedBy="particips")
     * @ORM\JoinColumn(nullable=false ,onDelete="CASCADE")
     */
    private $participant;
    /**
     *
     * @ORM\ManyToOne(targetEntity="FrontOffice\OptimusBundle\Entity\club", inversedBy="particips")
     * @ORM\JoinColumn(nullable=true)
     */
    private $club;

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
     * @param \FrontOffice\OptimusBundle\Entity\Member $participant
     * @return ParticipCompetition
     */
    public function setParticipant(\FrontOffice\OptimusBundle\Entity\Member $participant)
    {
        $this->participant = $participant;
    
        return $this;
    }

    /**
     * Get participant
     *
     * @return \FrontOffice\OptimusBundle\Entity\Member 
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * Set club
     *
     * @param \FrontOffice\OptimusBundle\Entity\club $club
     * @return ParticipCompetition
     */
    public function setClub(\FrontOffice\OptimusBundle\Entity\club $club)
    {
        $this->club = $club;
    
        return $this;
    }

    /**
     * Get club
     *
     * @return \FrontOffice\OptimusBundle\Entity\club 
     */
    public function getClub()
    {
        return $this->club;
    }
}