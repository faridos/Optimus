<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use \DateTime;
/**
 * Event
 *
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\CompetitionRepository")
 * @ORM\Table(name="competition")
 * @ORM\HasLifecycleCallbacks
 * @Assert\Callback(methods={{ "FrontOffice\OptimusBundle\Validator\Constraints\ContraintValidDateValidator", "isDateValid"}})
 */
class Competition {

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
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="competitions")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id" ,onDelete="CASCADE")
     **/
   
   protected $club;
    
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
     * @ORM\Column(name="nbrvu", type="integer", nullable=true)
     */
    private $nbrvu;
/**
     * @var boolean $activer
     * @ORM\Column(name="activation", type="boolean", nullable=false)
     */
    protected $active;
   

    /**
    * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Photo", mappedBy="competition")
    **/
    protected $imagesCompetition;
    
    
     /**
    * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\ParticipCompetition", mappedBy="competition")
    **/
    protected $particips;
     /**
    * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Sponsor", mappedBy="competition")
    **/
    protected $sponserc;

    
    public function __construct() {
       
        $this->dateCreation = new \Datetime();
    }


   
    function getDateModification() {
        return $this->dateModification;
    }

    function setDateModification($dateModification) {
        $this->dateModification = $dateModification;
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
        return 'upload/competition/' . $this->getId();
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Competition
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }
    function getActive() {
        return $this->active;
    }

    function setActive($active) {
        $this->active = $active;
    }

        /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    function getDateCreation() {
        return $this->dateCreation;
    }

    function setDateCreation(\DateTime $dateCreation) {
        $this->dateCreation = $dateCreation;
    }

        /**
     * Set path
     *
     * @param string $path
     * @return Competition
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Competition
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     * @return Competition
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    
        return $this;
    }

    /**
     * Get lieu
     *
     * @return string 
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Competition
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    
        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     * @return Competition
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    
        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime 
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set nbrPlaces
     *
     * @param integer $nbrPlaces
     * @return Competition
     */
    public function setNbrPlaces($nbrPlaces)
    {
        $this->nbrPlaces = $nbrPlaces;
    
        return $this;
    }

    /**
     * Get nbrPlaces
     *
     * @return integer 
     */
    public function getNbrPlaces()
    {
        return $this->nbrPlaces;
    }

    /**
     * Set frais
     *
     * @param float $frais
     * @return Competition
     */
    public function setFrais($frais)
    {
        $this->frais = $frais;
    
        return $this;
    }

    /**
     * Get frais
     *
     * @return float 
     */
    public function getFrais()
    {
        return $this->frais;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Competition
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Competition
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    
        return $this;
    }

    /**
     * Get lat
     *
     * @return float 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     * @return Competition
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    
        return $this;
    }

    /**
     * Get lng
     *
     * @return float 
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set nbrvu
     *
     * @param integer $nbrvu
     * @return Competition
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
     * Add imagesCompetition
     *
     * @param \FrontOffice\OptimusBundle\Entity\Photo $imagesCompetition
     * @return Competition
     */
    public function addImagesCompetition(\FrontOffice\OptimusBundle\Entity\Photo $imagesCompetition)
    {
        $this->imagesCompetition[] = $imagesCompetition;
    
        return $this;
    }

    /**
     * Remove imagesCompetition
     *
     * @param \FrontOffice\OptimusBundle\Entity\Photo $imagesCompetition
     */
    public function removeImagesCompetition(\FrontOffice\OptimusBundle\Entity\Photo $imagesCompetition)
    {
        $this->imagesCompetition->removeElement($imagesCompetition);
    }

    /**
     * Get imagesCompetition
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImagesCompetition()
    {
        return $this->imagesCompetition;
    }

    /**
     * Set club
     *
     * @param \FrontOffice\OptimusBundle\Entity\Club $club
     * @return Competition
     */
    public function setClub(\FrontOffice\OptimusBundle\Entity\Club $club = null)
    {
        $this->club = $club;
    
        return $this;
    }

    /**
     * Get club
     *
     * @return \FrontOffice\OptimusBundle\Entity\Club 
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * Add particips
     *
     * @param \FrontOffice\OptimusBundle\Entity\ParticipCompetition $particips
     * @return Competition
     */
    public function addParticip(\FrontOffice\OptimusBundle\Entity\ParticipCompetition $particips)
    {
        $this->particips[] = $particips;
    
        return $this;
    }

    /**
     * Remove particips
     *
     * @param \FrontOffice\OptimusBundle\Entity\ParticipCompetition $particips
     */
    public function removeParticip(\FrontOffice\OptimusBundle\Entity\ParticipCompetition $particips)
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

    /**
     * Add sponserc
     *
     * @param \FrontOffice\OptimusBundle\Entity\ParticipCompetition $sponserc
     * @return Competition
     */
    public function addSponserc(\FrontOffice\OptimusBundle\Entity\ParticipCompetition $sponserc)
    {
        $this->sponserc[] = $sponserc;
    
        return $this;
    }

    /**
     * Remove sponserc
     *
     * @param \FrontOffice\OptimusBundle\Entity\ParticipCompetition $sponserc
     */
    public function removeSponserc(\FrontOffice\OptimusBundle\Entity\ParticipCompetition $sponserc)
    {
        $this->sponserc->removeElement($sponserc);
    }

    /**
     * Get sponserc
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSponserc()
    {
        return $this->sponserc;
    }
}