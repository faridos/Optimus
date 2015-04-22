<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Message {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     */
    private $id;

    /**
     * @var sender
     * 
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User")
     */
    protected $sender;

    /**
     * @var integer
     *
     * @ORM\Column(name="reciever", type="integer")
     * 
     */
    protected $reciever;

    /**
     * @ORM\ManyToOne(targetEntity="FrontOffice\OptimusBundle\Entity\Conversation",inversedBy="messages", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="convers_id", referencedColumnName="id", nullable=true ,onDelete="CASCADE")
     */
    protected $conversation;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="msg_time", type="datetime")
     */
    private $msgTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_seen", type="boolean", nullable=true)
     */
    private $is_seen;
      /**
     * @var integer
     *
     * @ORM\Column(name="vu", type="integer", nullable=true)
     */
    private $vu;
    /**
     * @var integer
     *
     * @ORM\Column(name="event", type="integer", nullable=true)
     * 
     */
    private $event;
    /**
     * @var integer
     *
     * @ORM\Column(name="club", type="integer", nullable=true)
     * 
     */
    private $club;
    /**
     * @var integer
     *
     * @ORM\Column(name="competition", type="integer", nullable=true)
     * 
     */
    private $competition;
    

    public function __construct() {

        $this->is_seen = false;
		$this->vu = 0;
//        $this->dateCreation = new \DateTime();
        // your own logic
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }
    public function getClub() {
        return $this->club;
    }

    public function setClub($club) {
        $this->club = $club;
    }

        /**
     * Set content
     *
     * @param string $content
     * @return Message
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }
    public function getConversation() {
        return $this->conversation;
    }

    public function setConversation($conversation) {
        $this->conversation = $conversation;
    }

    
        /**
     * Get content
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }
    public function getEvent() {
        return $this->event;
    }

    public function setEvent($event) {
        $this->event = $event;
    }

    
    /**
     * Set msgTime
     *
     * @param \DateTime $msgTime
     * @return Message
     */
    public function setMsgTime($msgTime) {
        $this->msgTime = $msgTime;

        return $this;
    }

    /**
     * Get msgTime
     *
     * @return \DateTime 
     */
    public function getMsgTime() {
        return $this->msgTime;
    }

    /**
     * Set sender
     *
     * @param \FrontOffice\UserBundle\Entity\User $sender
     * @return Message
     */
    public function setSender(\FrontOffice\UserBundle\Entity\User $sender = null) {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \FrontOffice\UserBundle\Entity\User 
     */
    public function getSender() {
        return $this->sender;
    }

    public function setReciever($reciever) {
        $this->reciever = $reciever;
    }

    /**
     * Get reciever
     *
     * @return \FrontOffice\UserBundle\Entity\User 
     */
    public function getReciever() {
        return $this->reciever;
    }

    /**
     * @ORM\PrePersist
     */
    public function sendDate() {

        $this->setMsgTime(new \Datetime());
    }

    /**
     * Set is_seen
     *
     * @param boolean $isSeen
     * @return Message
     */
    public function setIsSeen($isSeen = false) {
        $this->is_seen = $isSeen;

        return $this;
    }

    /**
     * Get is_seen
     *
     * @return boolean 
     */
    public function getIsSeen() {
        return $this->is_seen;
    }

    public function getDuréeMsg() {
        $date = new \DateTime();
        $diff = $date->diff($this->msgTime);
        $durée = "";
        if ($diff->d >= 1):
            $durée = "il y'a " . $diff->d . " days";
        elseif ($diff->h >= 1):
            $durée = "il y'a " . $diff->h . " heur";
        elseif ($diff->i > 1):
            $durée = "il y'a " . $diff->i . " min";
        else:
            $durée = "Just Now";
        endif;
        return $durée;
    }
    
    
    public function getIs_seen() {
        return $this->is_seen;
    }

    public function getVu() {
        return $this->vu;
    }

    public function setIs_seen($is_seen) {
        $this->is_seen = $is_seen;
    }

    public function setVu($vu) {
        $this->vu = $vu;
    }



         public function __toString()
    {
        return (string) $this->getId();
    }

    /**
     * Set competition
     *
     * @param integer $competition
     * @return Message
     */
    public function setCompetition($competition)
    {
        $this->competition = $competition;
    
        return $this;
    }

    /**
     * Get competition
     *
     * @return integer 
     */
    public function getCompetition()
    {
        return $this->competition;
    }
}