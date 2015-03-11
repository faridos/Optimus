<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Event
 *
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\EventRepository")
 * @ORM\Table(name="event")
 * @Assert\Callback(methods={{ "FrontOffice\OptimusBundle\Validator\Constraints\ContraintValidDateValidator", "isDateValid"}})
 */
class Event {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="TypeEvent",inversedBy="evenement", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User",inversedBy="evenements", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $createur;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=100)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=100)
     */
    private $lieu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="datetime")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="datetime")
     */
    private $dateFin;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbr_places", type="smallint")
     */
    private $nbrPlaces;

    /**
     * @var float
     *
     * @ORM\Column(name="frais", type="float")
     */
    private $frais;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="lat", type="float", nullable=true)
     */
    private $lat;

    /**
     * @var float
     *
     * @ORM\Column(name="lng", type="float", nullable=true)
     */
    private $lng;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     */
    private $status;

    /**
     * @var boolean $activer
     * @ORM\Column(name="activation", type="boolean", nullable=false)
     */
    protected $active;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Notification", mappedBy="event", cascade={"persist","remove"})
     */
    protected $notification_event;
    
    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Comment", mappedBy="event" )
     *
     */
     protected $eventcomments;

    public function __construct() {
        $this->dateCreation = new \Datetime();
        $this->eventcomments = new ArrayCollection();
        $this->setDescription("Pas de description");
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Event
     */
    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     * @return Event
     */
    public function setLieu($lieu) {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string 
     */
    public function getLieu() {
        return $this->lieu;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Event
     */
    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation() {
        return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return Event
     */
    public function setDateModification($dateModification) {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime 
     */
    public function getDateModification() {
        return $this->dateModification;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Event
     */
    public function setDateDebut($dateDebut) {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut() {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Event
     */
    public function setDateFin($dateFin) {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin() {
        return $this->dateFin;
    }

    /**
     * Set nbrPlaces
     *
     * @param integer $nbrPlaces
     * @return Event
     */
    public function setNbrPlaces($nbrPlaces) {
        $this->nbrPlaces = $nbrPlaces;

        return $this;
    }

    /**
     * Get nbrPlaces
     *
     * @return integer 
     */
    public function getNbrPlaces() {
        return $this->nbrPlaces;
    }

    /**
     * Set frais
     *
     * @param float $frais
     * @return Event
     */
    public function setFrais($frais) {
        $this->frais = $frais;

        return $this;
    }

    /**
     * Get frais
     *
     * @return float 
     */
    public function getFrais() {
        return $this->frais;
    }


    /**
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Event
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set type
     *
     * @param \FrontOffice\OptimusBundle\Entity\TypeEvent $type
     * @return Event
     */
    public function setType(\FrontOffice\OptimusBundle\Entity\TypeEvent $type = null) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \FrontOffice\OptimusBundle\Entity\TypeEvent 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set createur
     *
     * @param \FrontOffice\UserBundle\Entity\User $createur
     * @return Event
     */
    public function setCreateur(\FrontOffice\UserBundle\Entity\User $createur = null) {
        $this->createur = $createur;

        return $this;
    }

    /**
     * Get createur
     *
     * @return \FrontOffice\UserBundle\Entity\User 
     */
    public function getCreateur() {
        return $this->createur;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Event
     */
    public function setLat($lat) {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return Event
     */
    public function setLng($lng) {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng() {
        return $this->lng;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Event
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive() {
        return $this->active;
    }

    public function __toString() {
        return (string) $this->getId();
    }

    /**
     * Add eventcomments
     *
     * @param \FrontOffice\OptimusBundle\Entity\Comment $eventcomments
     * @return Event
     */
    public function addEventcomment(\FrontOffice\OptimusBundle\Entity\Comment $eventcomments)
    {
        $this->eventcomments[] = $eventcomments;

        return $this;
    }

    /**
     * Remove eventcomments
     *
     * @param \FrontOffice\OptimusBundle\Entity\Comment $eventcomments
     */
    public function removeEventcomment(\FrontOffice\OptimusBundle\Entity\Comment $eventcomments)
    {
        $this->eventcomments->removeElement($eventcomments);
    }

    /**
     * Get eventcomments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEventcomments()
    {
        return $this->eventcomments;
    }
}
