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
     * @ORM\OneToMany(targetEntity="CompteClub", mappedBy="member", cascade={"persist","remove"})
     * */
    protected $compte;
   /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_sent", type="datetime")
     */
   private $datesent;
   /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_confirm", type="datetime", nullable=true)
     */
   private $dateconfirm;
   /**
     * @var integer $confirmed
     * @ORM\Column(name="confirmed", type="integer", nullable=false)
     */
   protected $confirmed; 
   
    /**
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="adherent")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id" ,onDelete="CASCADE")
     **/
   
   protected $member;
     /**

     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\PartClubCompetition", mappedBy="participant", cascade={"persist","remove"})

     */

    protected $particips;
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

    /**
     * Add compte
     *
     * @param \FrontOffice\OptimusBundle\Entity\CompteClub $compte
     * @return Member
     */
    public function addCompte(\FrontOffice\OptimusBundle\Entity\CompteClub $compte)
    {
        $this->compte[] = $compte;
    
        return $this;
    }

    /**
     * Remove compte
     *
     * @param \FrontOffice\OptimusBundle\Entity\CompteClub $compte
     */
    public function removeCompte(\FrontOffice\OptimusBundle\Entity\CompteClub $compte)
    {
        $this->compte->removeElement($compte);
    }

    /**
     * Get compte
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompte()
    {
        return $this->compte;
    }

   

    /**
     * Add particips
     *
     * @param \FrontOffice\OptimusBundle\Entity\PartClubCompetition $particips
     * @return Member
     */
    public function addParticip(\FrontOffice\OptimusBundle\Entity\PartClubCompetition $particips)
    {
        $this->particips[] = $particips;
    
        return $this;
    }

    /**
     * Remove particips
     *
     * @param \FrontOffice\OptimusBundle\Entity\PartClubCompetition $particips
     */
    public function removeParticip(\FrontOffice\OptimusBundle\Entity\PartClubCompetition $particips)
    {
        $this->particips->removeElement($particips);
    }

    /**
     * Get particips
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticips()
    {
        return $this->particips;
    }
}