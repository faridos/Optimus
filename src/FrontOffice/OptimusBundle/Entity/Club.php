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
     * @var \DateTime
     *
     * @ORM\Column(name="Date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="Discpline", type="string", length=255)
     */
    private $discpline;

    /**
     * @var string
     *
     * @ORM\Column(name="Adresse", type="string", length=255)
     */
    private $adresse;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
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
     * @var float
     *
     * @ORM\Column(name="Frais_adhesion", type="float")
     */
    private $fraisAdhesion;

    /**
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="clubs")
     * @ORM\JoinColumn(name="createur_id", referencedColumnName="id")
     * */
    protected $createur;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="club", cascade={"persist","remove"})
     */
    protected $notification_club;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Comment", mappedBy="club" )
     *
     */
    protected $clubcomments;

    /**
     * @ORM\OneToMany(targetEntity="Member", mappedBy="clubad")
     * */
    protected $adherents;
    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Reward", mappedBy="club")
     * 
     */
    protected $reward;

    public function __construct() {

        $this->clubcomments = new ArrayCollection();
//        $this->dateCreation = new \DateTime();
        // your own logic
    }
    public function getDescription() {
        return $this->description;
    }

    public function getNotification_club() {
        return $this->notification_club;
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

    public function setNotification_club($notification_club) {
        $this->notification_club = $notification_club;
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
     * @param \DateTime $dateCreation
     * @return Club
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

}
