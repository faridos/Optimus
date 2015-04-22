<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * PartClubCompetition
 *
 * @ORM\Table(name="partclubcompetition")
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\PartClubCompetitionRepository")
 */
class PartClubCompetition {
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
     * @ORM\ManyToOne(targetEntity="FrontOffice\OptimusBundle\Entity\ParticipCompetition", inversedBy="partclubcomp")
     * @ORM\JoinColumn(nullable=true,onDelete="CASCADE")
     */
    private $particips;
         
  /**
     *
     * @ORM\ManyToOne(targetEntity="FrontOffice\OptimusBundle\Entity\Member", inversedBy="particips")
     * @ORM\JoinColumn(nullable=false ,onDelete="CASCADE")
     */
    private $participant;


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
     * Set participant
     *
     * @param \FrontOffice\OptimusBundle\Entity\Member $participant
     * @return PartClubCompetition
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
     * Set particips
     *
     * @param \FrontOffice\OptimusBundle\Entity\ParticipCompetition $particips
     * @return PartClubCompetition
     */
    public function setParticips(\FrontOffice\OptimusBundle\Entity\ParticipCompetition $particips = null)
    {
        $this->particips = $particips;
    
        return $this;
    }

    /**
     * Get particips
     *
     * @return \FrontOffice\OptimusBundle\Entity\ParticipCompetition 
     */
    public function getParticips()
    {
        return $this->particips;
    }
}