<?php

namespace FrontOffice\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;

/**
 * User
 * @ORM\Entity(repositoryClass="FrontOffice\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $nom;

    /**
     * @ORM\Column(type="string")
     */
    protected $prenom;

    /**
     * @ORM\Column(type="date")
     */
    protected $dateNaissance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", name="date_creation")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="string")
     * @Assert\Choice(choices = {"H","F"})
     */
    protected $sexe;

    /**
     * @ORM\Column(type="string")
     */
    protected $adresse;

    /**
     * @ORM\Column(type="phone_number", nullable=true)
     * @AssertPhoneNumber(message="Tel invalide !")
     * @Assert\Length(
     *      min = "8",
     *      max = "12",
     *      minMessage = "Au moins {{ limit }} caractères !",
     *      maxMessage = "Ne peut pas être plus long que {{ limit }} caractères !"
     * )
     */
    protected $tel;

    /**
     * @ORM\Column(type="string")
     * @Assert\Choice(choices = {"S","E"})
     */
    protected $profil;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(choices = {"All","NOT","EC","EU","UC","E","C","U"})
     */
    protected $type_notification;

    /**
     * @ORM\Column(type="string", length=255,  nullable=true )
     */
    protected $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

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
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Event", mappedBy="createur", cascade={"persist","remove"})
     */
    protected $evenements;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Participation", mappedBy="participant", cascade={"persist","remove"})
     */
    protected $participations;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Notification", mappedBy="entraineur", cascade={"persist","remove"})
     */
    protected $notification_entraineur;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\NotificationSeen", mappedBy="users")
     * */
    protected $notificationseen;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Club", mappedBy="createur")
     * */
    protected $clubs;

    /**
     * @var conversations1
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Conversation" , mappedBy="user1" )
     */
    protected $conversations1;

    /**
     * @var conversations2
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Conversation" , mappedBy="user2" )
     */
    protected $conversations2;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Album", mappedBy="user")
     * */
    protected $albums;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Reward", mappedBy="user")
     * 
     */
    protected $reward;

    /**
     * @ORM\OneToMany(targetEntity="FrontOffice\OptimusBundle\Entity\Member", mappedBy="member")
     * */
    protected $adherent;

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt) {
        $this->createdAt = $createdAt;
    }

    public function getTel() {
        return $this->tel;
    }

    public function setTel($tel) {
        $this->tel = $tel;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getDateNaissance() {
        return $this->dateNaissance;
    }

    public function getSexe() {
        return $this->sexe;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function getProfil() {
        return $this->profil;
    }

    public function getType_notification() {
        return $this->type_notification;
    }

    public function getPath() {
        return $this->path;
    }

    public function getFile() {
        return $this->file;
    }

    public function getLat() {
        return $this->lat;
    }

    public function getLng() {
        return $this->lng;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setDateNaissance($dateNaissance) {
        $this->dateNaissance = $dateNaissance;
    }

    public function setSexe($sexe) {
        $this->sexe = $sexe;
    }

    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    public function setProfil($profil) {
        $this->profil = $profil;
    }

    public function setType_notification($type_notification) {
        $this->type_notification = $type_notification;
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

    public function setLat($lat) {
        $this->lat = $lat;
    }

    public function setLng($lng) {
        $this->lng = $lng;
    }

    public function __construct() {
        parent::__construct();
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
        return 'upload/profil/' . $this->getId();
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

}
