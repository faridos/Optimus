<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\MessageRepository")
 * * @ORM\HasLifecycleCallbacks()
 */
class Message
{
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
     * @var reciever
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User")
     */
    protected $reciever;
    /**
     * @var conversationroom
     * @ORM\ManyToOne(targetEntity="FrontOffice\OptimusBundle\Entity\Conversation" , inversedBy="messages")
     */
    protected $conversationroom;
    

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
    private  $is_seen;
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
     * Set content
     *
     * @param string $content
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set msgTime
     *
     * @param \DateTime $msgTime
     * @return Message
     */
    public function setMsgTime($msgTime)
    {
        $this->msgTime = $msgTime;

        return $this;
    }

    /**
     * Get msgTime
     *
     * @return \DateTime 
     */
    public function getMsgTime()
    {
        return $this->msgTime;
    }

    /**
     * Set sender
     *
     * @param \FrontOffice\UserBundle\Entity\User $sender
     * @return Message
     */
    public function setSender(\FrontOffice\UserBundle\Entity\User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \FrontOffice\UserBundle\Entity\User 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set reciever
     *
     * @param \FrontOffice\UserBundle\Entity\User $reciever
     * @return Message
     */
    public function setReciever(\FrontOffice\UserBundle\Entity\User $reciever = null)
    {
        $this->reciever = $reciever;

        return $this;
    }

    /**
     * Get reciever
     *
     * @return \FrontOffice\UserBundle\Entity\User 
     */
    public function getReciever()
    {
        return $this->reciever;
    }

    /**
     * Set conversationroom
     *
     * @param \FrontOffice\OptimusBundle\Entity\Conversation $conversationroom
     * @return Message
     */
    public function setConversationroom(\FrontOffice\OptimusBundle\Entity\Conversation $conversationroom = null)
    {
        $this->conversationroom = $conversationroom;

        return $this;
    }

    /**
     * Get conversationroom
     *
     * @return \FrontOffice\OptimusBundle\Entity\Conversation 
     */
    public function getConversationroom()
    {
        return $this->conversationroom;
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
    public function setIsSeen($isSeen)
    {
        $this->is_seen = $isSeen;

        return $this;
    }

    /**
     * Get is_seen
     *
     * @return boolean 
     */
    public function getIsSeen()
    {
        return $this->is_seen;
    }
    
//         public function __toString()
//    {
//        return (string) $this->getId();
//    }
}
