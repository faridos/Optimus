<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Configuration
 *
 * @ORM\Table(name="confignotif")
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Entity\ConfigNotifRepository")
 */
class ConfigNotif
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
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="configNotif")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * 
     */
   protected $user;
    /**
     * @var integer
     *
     * @ORM\Column(name="club", type="integer", nullable=true)
     */
    private $club;
     /**
     * @var integer
     *
     * @ORM\Column(name="event", type="integer", nullable=true)
     */
    private $event;
     /**
     * @var integer
     *
     * @ORM\Column(name="entraineur", type="integer", nullable=true)
     */
    private $entraineur;

      public function __construct() {
       
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

    function getClub() {
        return $this->club;
    }

    function getEvent() {
        return $this->event;
    }

    function getEntraineur() {
        return $this->entraineur;
    }

    function setClub($club) {
        $this->club = $club;
    }

    function setEvent($event) {
        $this->event = $event;
    }

    function setEntraineur($entraineur) {
        $this->entraineur = $entraineur;
    }




    /**
     * Set user
     *
     * @param \FrontOffice\UserBundle\Entity\User $user
     * @return ConfigNotif
     */
    public function setUser(\FrontOffice\UserBundle\Entity\User $user = null)
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