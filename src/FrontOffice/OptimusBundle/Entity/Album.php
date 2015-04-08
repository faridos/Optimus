<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Album
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\AlbumRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="album")
 */
class Album 
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
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="albums")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id" ,onDelete="CASCADE")
     **/
   
   protected $user;
   /**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="album")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id" ,onDelete="CASCADE")
     **/
   
   protected $club;
   
    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="album")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id" ,onDelete="CASCADE")
     **/
   
   protected $event;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_create", type="date")
     */
    private $createdate;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_update", type="date")
     */
    private $updatedate;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(choices = {"Unique","Amis","Publique"})
     */    
    protected $privacy;
     /**
    * @ORM\OneToMany(targetEntity="Photo", mappedBy="album", cascade={"persist","remove"})
    **/
    protected $images;
    //put your code here
    public function __construct()
    {
        
    }
    public function getId() {
        return $this->id;
    }
    public function getEvent() {
        return $this->event;
    }

    public function setEvent($event) {
        $this->event = $event;
    }

        public function getNom() {
        return $this->nom;
    }
    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

        public function getCreatedate() {
        return $this->createdate;
    }

    public function getUpdatedate() {
        return $this->updatedate;
    }

    public function getPrivacy() {
        return $this->privacy;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setCreatedate(\DateTime $createdate) {
        $this->createdate = $createdate;
    }

   

    public function setPrivacy($privacy) {
        $this->privacy = $privacy;
    }
    

    public function getClub() {
        return $this->club;
    }

    public function setUpdatedate(\DateTime $updatedate) {
        $this->updatedate = $updatedate;
    }

    
    public function setClub($club) {
        $this->club = $club;
    }
    
    public function __toString()
    {
        return (string) $this->getNom();
    }



    /**
     * Add images
     *
     * @param \FrontOffice\OptimusBundle\Entity\Photo $images
     * @return Album
     */
    public function addImage(\FrontOffice\OptimusBundle\Entity\Photo $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \FrontOffice\OptimusBundle\Entity\Photo $images
     */
    public function removeImage(\FrontOffice\OptimusBundle\Entity\Photo $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }
}