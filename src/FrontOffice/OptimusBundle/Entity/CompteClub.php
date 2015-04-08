<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * CompteClub
 *
 * @ORM\Table(name="compteclub")
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\CompteClubRepository")
 */
class CompteClub {
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_exit", type="datetime", nullable=true)
     */
   private $dateExit;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_active", type="datetime", nullable=true)
     */
   private $dateActive;
    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="compte")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id" ,onDelete="CASCADE")
     **/
   protected $member;
    /**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="comptem")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id" ,onDelete="CASCADE")
     **/
   protected $club;
    //put your code here

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
     * Set type
     *
     * @param string $type
     * @return CompteClub
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set member
     *
     * @param \FrontOffice\OptimusBundle\Entity\Member $member
     * @return CompteClub
     */
    public function setMember(\FrontOffice\OptimusBundle\Entity\Member $member = null)
    {
        $this->member = $member;
    
        return $this;
    }
    public function getDateExit() {
        return $this->dateExit;
    }

    public function getDateActive() {
        return $this->dateActive;
    }

    public function setDateExit(\DateTime $dateExit) {
        $this->dateExit = $dateExit;
    }

    public function setDateActive(\DateTime $dateActive) {
        $this->dateActive = $dateActive;
    }

        /**
     * Get member
     *
     * @return \FrontOffice\OptimusBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set club
     *
     * @param \FrontOffice\OptimusBundle\Entity\Club $club
     * @return CompteClub
     */
    public function setClub(\FrontOffice\OptimusBundle\Entity\Club $club = null)
    {
        $this->club = $club;
    
        return $this;
    }

    /**
     * Get club
     *
     * @return \FrontOffice\OptimusBundle\Entity\Club 
     */
    public function getClub()
    {
        return $this->club;
    }
}