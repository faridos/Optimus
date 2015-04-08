<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoryClub
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\HistoryClubRepository")
 * @ORM\Table(name="historyclub")
 * 
 */
class HistoryClub
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
     * @ORM\ManyToOne(targetEntity="Club", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false ,onDelete="CASCADE")
     */
    private $club;
    
    /**
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User",cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false ,onDelete="CASCADE")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=50)
     */
    private $action;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->date = new \Datetime(); 
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
     * Set action
     *
     * @param string $action
     * @return HistoryClub
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return HistoryClub
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set club
     *
     * @param \FrontOffice\OptimusBundle\Entity\Club $club
     * @return HistoryClub
     */
    public function setClub(\FrontOffice\OptimusBundle\Entity\Club $club)
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

    /**
     * Set user
     *
     * @param \FrontOffice\UserBundle\Entity\User $user
     * @return HistoryClub
     */
    public function setUser(\FrontOffice\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \FrontOffice\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}