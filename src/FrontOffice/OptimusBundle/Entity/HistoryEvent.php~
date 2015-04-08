<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoryEvent
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\HistoryEventRepository")
 * @ORM\Table(name="historyevent")
 * 
 */
class HistoryEvent
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
     * @ORM\ManyToOne(targetEntity="Event", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false ,onDelete="CASCADE")
     */
    private $event;
    
    
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
     * @return HistoryEvent
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
     * @return HistoryEvent
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
     * Set event
     *
     * @param \FrontOffice\OptimusBundle\Entity\Event $event
     * @return HistoryEvent
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
     * Set user
     *
     * @param \FrontOffice\UserBundle\Entity\User $user
     * @return HistoryEvent
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