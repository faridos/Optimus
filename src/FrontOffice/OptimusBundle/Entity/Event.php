<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Event
 *
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\EventRepository")
 * @ORM\Table(name="event")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User",inversedBy="evenements", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false ,onDelete="CASCADE")
     */
    private $createur;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

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
     * @ORM\Column(name="nbr_places", type="smallint", nullable=true)
     */
    private $nbrPlaces;

    /**
     * @var float
     *
     * @ORM\Column(name="frais", type="float", nullable=true)
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
     * @var integer
     *
     * @ORM\Column(name="nbrvu", type="integer", nullable=true)
     */
    private $nbrvu;

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
     * @ORM\OneToMany(targetEntity="Sponsor", mappedBy="event", cascade={"persist","remove"})
     */
    protected $sponsere;
    
    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Comment", mappedBy="event" )
     *
     */
     protected $eventcomments;

    /**
    * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Photo", mappedBy="event")
    **/
    protected $images;
      /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Album", mappedBy="event")
     * */
    protected $album;
    
    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Participation", mappedBy="event")
     */
    protected $participations;
    
    public function __construct() {
        $this->dateCreation = new \Datetime();
        $this->eventcomments = new ArrayCollection();
        $this->images = new ArrayCollection();
       
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
    public function getPath() {
        return $this->path;
    }
 public function setPath($path) {
        $this->path = $path;
    }
    public function getFile() {
        return $this->file;
    }

   

     public function setFile(UploadedFile $file = null) {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
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
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
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
    
    /**
     * Add images
     *
     * @param \FrontOffice\OptimusBundle\Entity\Photo $images
     * @return Photo
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

    /**
     * Add notification_event
     *
     * @param \FrontOffice\OptimusBundle\Entity\Notification $notificationEvent
     * @return Event
     */
    public function addNotificationEvent(\FrontOffice\OptimusBundle\Entity\Notification $notificationEvent)
    {
        $this->notification_event[] = $notificationEvent;
    
        return $this;
    }

    /**
     * Remove notification_event
     *
     * @param \FrontOffice\OptimusBundle\Entity\Notification $notificationEvent
     */
    public function removeNotificationEvent(\FrontOffice\OptimusBundle\Entity\Notification $notificationEvent)
    {
        $this->notification_event->removeElement($notificationEvent);
    }

    /**
     * Get notification_event
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotificationEvent()
    {
        return $this->notification_event;
    }

    /**
     * Add album
     *
     * @param \FrontOffice\OptimusBundle\Entity\Album $album
     * @return Event
     */
    public function addAlbum(\FrontOffice\OptimusBundle\Entity\Album $album)
    {
        $this->album[] = $album;
    
        return $this;
    }

    /**
     * Remove album
     *
     * @param \FrontOffice\OptimusBundle\Entity\Album $album
     */
    public function removeAlbum(\FrontOffice\OptimusBundle\Entity\Album $album)
    {
        $this->album->removeElement($album);
    }

    /**
     * Get album
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * Add participations
     *
     * @param \FrontOffice\OptimusBundle\Entity\Participation $participations
     * @return Event
     */
    public function addParticipation(\FrontOffice\OptimusBundle\Entity\Participation $participations)
    {
        $this->participations[] = $participations;
    
        return $this;
    }

    /**
     * Remove participations
     *
     * @param \FrontOffice\OptimusBundle\Entity\Participation $participations
     */
    public function removeParticipation(\FrontOffice\OptimusBundle\Entity\Participation $participations)
    {
        $this->participations->removeElement($participations);
    }

    /**
     * Get participations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticipations()
    {
        return $this->participations;
    }
    public function getAbsolutePath() {
        return null === $this->path ? null : $this->getUploadRootDir() . '/' . $this->path;
    }

    public function getWebPath() {
        return null === $this->path ? null : $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'upload/event/' . $this->getId();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        $this->tempFile = $this->getWebPath();
        $this->oldFile = $this->getPath();
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->path = sha1(uniqid(mt_rand(), true)) . '.' . $this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->file) {
            return;
        }

        // s'il y a une erreur lors du déplacement du fichier, une exception
        // va automatiquement être lancée par la méthode move(). Cela va empêcher
        // proprement l'entité d'être persistée dans la base de données si
        // erreur il y a
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }


    /**
     * Set nbrvu
     *
     * @param integer $nbrvu
     * @return Event
     */
    public function setNbrvu($nbrvu)
    {
        $this->nbrvu = $nbrvu;
    
        return $this;
    }

    /**
     * Get nbrvu
     *
     * @return integer 
     */
    public function getNbrvu()
    {
        return $this->nbrvu;
    }

    /**
     * Add sponsere
     *
     * @param \FrontOffice\OptimusBundle\Entity\Notification $sponsere
     * @return Event
     */
    public function addSponsere(\FrontOffice\OptimusBundle\Entity\Notification $sponsere)
    {
        $this->sponsere[] = $sponsere;
    
        return $this;
    }

    /**
     * Remove sponsere
     *
     * @param \FrontOffice\OptimusBundle\Entity\Notification $sponsere
     */
    public function removeSponsere(\FrontOffice\OptimusBundle\Entity\Notification $sponsere)
    {
        $this->sponsere->removeElement($sponsere);
    }

    /**
     * Get sponsere
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSponsere()
    {
        return $this->sponsere;
    }
}