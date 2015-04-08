<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Member
 *
 * @ORM\Table(name="member")
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\MemberRepository")
 */
class Member 
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
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="adherents")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id" ,onDelete="CASCADE")
     **/
   protected $clubad;
   /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_sent", type="date")
     */
   private $datesent;
   /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_confirm", type="date", nullable=true)
     */
   private $dateconfirm;
   /**
     * @var integer $confirmed
     * @ORM\Column(name="confirmed", type="integer", nullable=false)
     */
   protected $confirmed; 
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_exit", type="date", nullable=true)
     */
   private $dateExit;
    /**
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="adherent")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id" ,onDelete="CASCADE")
     **/
   
   protected $member;
   public function __construct()
    {
        $this->confirmed=false;
        $this->datesent = new \DateTime('now');
//        if($this->confirmed==true)
//        {
//            $this->dateconfirm = new \DateTime('now');
//        }
//        
      

    }
    public function getMember() {
        return $this->member;
    }

    public function setMember($member) {
        $this->member = $member;
    }

       public function getId() {
       return $this->id;
   }

  
   public function setId($id) {
       $this->id = $id;
   }
   public function getClubad() {
       return $this->clubad;
   }

   public function setClubad($clubad) {
       $this->clubad = $clubad;
   }
   public function getDatesent() {
       return $this->datesent;
   }

   public function setDatesent(\DateTime $datesent) {
       $this->datesent = $datesent;
   }
   public function getDateconfirm() {
       return $this->dateconfirm;
   }

   public function setDateconfirm(\DateTime $dateconfirm) {
       $this->dateconfirm = $dateconfirm;
   }

   public function getConfirmed() {
       return $this->confirmed;
   }

   public function setConfirmed($confirmed) {
       $this->confirmed = $confirmed;
   }

  
  



   


    /**
     * Set dateExit
     *
     * @param \DateTime $dateExit
     * @return Member
     */
    public function setDateExit($dateExit)
    {
        $this->dateExit = $dateExit;
    
        return $this;
    }

    /**
     * Get dateExit
     *
     * @return \DateTime 
     */
    public function getDateExit()
    {
        return $this->dateExit;
    }
}