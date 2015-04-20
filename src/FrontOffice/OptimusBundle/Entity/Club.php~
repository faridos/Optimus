<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Club
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\ClubRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="club")
 */
class Club {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    /**
     * @var \Date
     *
     * @ORM\Column(name="Date_creation", type="date", nullable=true)
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="Discpline", type="string", length=255, nullable=true)
     */
    private $discpline;

    /**
     * @var string
     *
     * @ORM\Column(name="Adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
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
     * @var string
     *
     * @ORM\Column(name="Lien_Web", type="string", length=255)
     */
    private $lienWeb;

    /**
     * @var boolean $activer
     * @ORM\Column(name="activation", type="boolean", nullable=false)
     */
    protected $active;

    /**
     * @var boolean $isPayant
     * @ORM\Column(name="payant", type="boolean")
     */
    protected $isPayant;

    /**
     * @var float
     *
     * @ORM\Column(name="frais", type="float", nullable=true)
     */
    private $fraisAdhesion;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbrvu", type="integer", nullable=true)
     */
    private $nbrvu;

    /**
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="clubs")
     * @ORM\JoinColumn(name="createur_id", referencedColumnName="id" ,onDelete="CASCADE")
     * */
    protected $createur;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="club", cascade={"persist","remove"})
     */
    protected $notificationclub;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Comment", mappedBy="club" )
     *
     */
    protected $clubcomments;

    /**
     * @ORM\OneToMany(targetEntity="Member", mappedBy="clubad", cascade={"persist","remove"})
     * */
    protected $adherents;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Reward", mappedBy="club")
     * 
     */
    protected $reward;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Album", mappedBy="club")
     * */
    protected $album;
    
     /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Competition", mappedBy="club")
     * */
    protected $competitions;
    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\ParticipCompetition", mappedBy="club")
     * */
    protected $particips;

    /**
     * @ORM\OneToMany(targetEntity="Program", mappedBy="clubp")
     * */
    protected $programs;
    /**
     * @ORM\OneToMany(targetEntity="CompteClub", mappedBy="club", cascade={"persist","remove"})
     * */
    protected $comptem;

    public function __construct() {

        $this->clubcomments = new ArrayCollection();
       $this->dateCreation = new \DateTime();
        // your own logic
    }

    public function getDescription() {
        return $this->description;
    }


    public function getAdherents() {
        return $this->adherents;
    }

    public function getClubs() {
        return $this->clubs;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

 

    public function setAdherents($adherents) {
        $this->adherents = $adherents;
    }

    public function setClubs($clubs) {
        $this->clubs = $clubs;
    }

    public function getActive() {
        return $this->active;
    }

    public function setActive($active) {
        $this->active = $active;
    }

    public function getCreateur() {
        return $this->createur;
    }

    public function setCreateur($createur) {
//       $createur=11;
        $this->createur = $createur;
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
     * Set nom
     *
     * @param string $nom
     * @return Club
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom() {
        return $this->nom;
    }

    protected $clubs;

    public function getPath() {
        return $this->path;
    }

    public function getFile() {
        return $this->file;
    }

    public function setPath($path) {
        $this->path = $path;
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
     * @param \Date $dateCreation
     * @return Club
     */
    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \Date
     */
    public function getDateCreation() {
        return $this->dateCreation;
    }

    /**
     * Set discpline
     *
     * @param string $discpline
     * @return Club
     */
    public function setDiscpline($discpline) {
        $this->discpline = $discpline;

        return $this;
    }

    /**
     * Get discpline
     *
     * @return string 
     */
    public function getDiscpline() {
        return $this->discpline;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Club
     */
    public function setAdresse($adresse) {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * Set lienWeb
     *
     * @param string $lienWeb
     * @return Club
     */
    public function setLienWeb($lienWeb) {
        $this->lienWeb = $lienWeb;

        return $this;
    }

    /**
     * Get lienWeb
     *
     * @return string 
     */
    public function getLienWeb() {
        return $this->lienWeb;
    }

    /**
     * Set fraisAdhesion
     *
     * @param float $fraisAdhesion
     * @return Club
     */
    public function setFraisAdhesion($fraisAdhesion) {
        $this->fraisAdhesion = $fraisAdhesion;

        return $this;
    }

    /**
     * Get fraisAdhesion
     *
     * @return float 
     */
    public function getFraisAdhesion() {
        return $this->fraisAdhesion;
    }

    /**
     * Set lat
     *
     * @param float $lat
     * @return Club
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
     * @return Club
     */
    public function setLng($lng) {
        $this->lng = $lng;

        return $this;
    }
    public function getNbrvu() {
        return $this->nbrvu;
    }

    public function setNbrvu($nbrvu) {
        $this->nbrvu = $nbrvu;
    }

        /**
     * Get lng
     *
     * @return float 
     */
    public function getLng() {
        return $this->lng;
    }

    public function __toString() {
        return (string) $this->getNom();
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
        return 'upload/club/' . $this->getId();
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
     * Remove clubcomments
     *
     * @param \FrontOffice\OptimusBundle\Entity\Comment $comment
     */
    public function addClubcomments(Comment $comment) {
        $this->clubcomments[] = $comment;

        return $this;
    }

    /**
     * Add clubcomments
     *
     * @param \FrontOffice\OptimusBundle\Entity\Comment $comment
     * @return Club
     */
    public function removeClubcomments(Comment $comment) {
        $this->clubcomments->removeElement($comment);
    }

    /**
     * Get eventcomments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClubcomments() {
        return $this->clubcomments;
    }

    

    

    /**
     * Add clubcomments
     *
     * @param \FrontOffice\OptimusBundle\Entity\Comment $clubcomments
     * @return Club
     */
    public function addClubcomment(\FrontOffice\OptimusBundle\Entity\Comment $clubcomments) {
        $this->clubcomments[] = $clubcomments;

        return $this;
    }

    /**
     * Remove clubcomments
     *
     * @param \FrontOffice\OptimusBundle\Entity\Comment $clubcomments
     */
    public function removeClubcomment(\FrontOffice\OptimusBundle\Entity\Comment $clubcomments) {
        $this->clubcomments->removeElement($clubcomments);
    }

    /**
     * Add adherents
     *
     * @param \FrontOffice\OptimusBundle\Entity\Member $adherents
     * @return Club
     */
    public function addAdherent(\FrontOffice\OptimusBundle\Entity\Member $adherents) {
        $this->adherents[] = $adherents;

        return $this;
    }

    /**
     * Remove adherents
     *
     * @param \FrontOffice\OptimusBundle\Entity\Member $adherents
     */
    public function removeAdherent(\FrontOffice\OptimusBundle\Entity\Member $adherents) {
        $this->adherents->removeElement($adherents);
    }

    /**
     * Add reward
     *
     * @param \FrontOffice\OptimusBundle\Entity\Reward $reward
     * @return Club
     */
    public function addReward(\FrontOffice\OptimusBundle\Entity\Reward $reward) {
        $this->reward[] = $reward;

        return $this;
    }

    /**
     * Remove reward
     *
     * @param \FrontOffice\OptimusBundle\Entity\Reward $reward
     */
    public function removeReward(\FrontOffice\OptimusBundle\Entity\Reward $reward) {
        $this->reward->removeElement($reward);
    }

    /**
     * Get reward
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReward() {
        return $this->reward;
    }

    /**
     * Add album
     *
     * @param \FrontOffice\OptimusBundle\Entity\Album $album
     * @return Club
     */
    public function addAlbum(\FrontOffice\OptimusBundle\Entity\Album $album) {
        $this->album[] = $album;

        return $this;
    }

    /**
     * Remove album
     *
     * @param \FrontOffice\OptimusBundle\Entity\Album $album
     */
    public function removeAlbum(\FrontOffice\OptimusBundle\Entity\Album $album) {
        $this->album->removeElement($album);
    }

    /**
     * Get album
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlbum() {
        return $this->album;
    }

    /**
     * Add programs
     *
     * @param \FrontOffice\OptimusBundle\Entity\Program $programs
     * @return Club
     */
    public function addProgram(\FrontOffice\OptimusBundle\Entity\Program $programs) {
        $this->programs[] = $programs;

        return $this;
    }

    /**
     * Remove programs
     *
     * @param \FrontOffice\OptimusBundle\Entity\Program $programs
     */
    public function removeProgram(\FrontOffice\OptimusBundle\Entity\Program $programs) {
        $this->programs->removeElement($programs);
    }

    /**
     * Get programs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrograms() {
        return $this->programs;
    }

    /**
     * Set isPayant
     *
     * @param boolean $isPayant
     * @return Club
     */
    public function setIsPayant($isPayant) {
        $this->isPayant = $isPayant;

        return $this;
    }

    /**
     * Get isPayant
     *
     * @return boolean 
     */
    public function getIsPayant() {
        return $this->isPayant;
    }


    /**
     * Add comptem
     *
     * @param \FrontOffice\OptimusBundle\Entity\CompteClub $comptem
     * @return Club
     */
    public function addComptem(\FrontOffice\OptimusBundle\Entity\CompteClub $comptem)
    {
        $this->comptem[] = $comptem;
    
        return $this;
    }

    /**
     * Remove comptem
     *
     * @param \FrontOffice\OptimusBundle\Entity\CompteClub $comptem
     */
    public function removeComptem(\FrontOffice\OptimusBundle\Entity\CompteClub $comptem)
    {
        $this->comptem->removeElement($comptem);
    }

    /**
     * Get comptem
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComptem()
    {
        return $this->comptem;
    }

    /**
     * Add notificationclub
     *
     * @param \FrontOffice\OptimusBundle\Entity\Notification $notificationclub
     * @return Club
     */
    public function addNotificationclub(\FrontOffice\OptimusBundle\Entity\Notification $notificationclub)
    {
        $this->notificationclub[] = $notificationclub;
    
        return $this;
    }

    /**
     * Remove notificationclub
     *
     * @param \FrontOffice\OptimusBundle\Entity\Notification $notificationclub
     */
    public function removeNotificationclub(\FrontOffice\OptimusBundle\Entity\Notification $notificationclub)
    {
        $this->notificationclub->removeElement($notificationclub);
    }

    /**
     * Get notificationclub
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotificationclub()
    {
        return $this->notificationclub;
    }

    /**
     * Add competitions
     *
     * @param \FrontOffice\OptimusBundle\Entity\Competition $competitions
     * @return Club
     */
    public function addCompetition(\FrontOffice\OptimusBundle\Entity\Competition $competitions)
    {
        $this->competitions[] = $competitions;
    
        return $this;
    }

    /**
     * Remove competitions
     *
     * @param \FrontOffice\OptimusBundle\Entity\Competition $competitions
     */
    public function removeCompetition(\FrontOffice\OptimusBundle\Entity\Competition $competitions)
    {
        $this->competitions->removeElement($competitions);
    }

    /**
     * Get competitions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCompetitions()
    {
        return $this->competitions;
    }

    /**
     * Add particips
     *
     * @param \FrontOffice\OptimusBundle\Entity\ParticipCompetition $particips
     * @return Club
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
}