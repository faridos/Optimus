<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Configuration
 *
 * @ORM\Table(name="configNotif")
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
        $this->club = 1;
        $this->event = 1;
        $this->entraineur = 1;
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


}