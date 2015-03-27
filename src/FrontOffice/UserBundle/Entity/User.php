<?php

namespace FrontOffice\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\UploadedFile;


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
     * @ORM\Column(type="date", nullable=true)
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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $adresse;

    /**
     * @ORM\Column(type="string", nullable=true)
     * 
     */
    protected $tel;

    /**
     * @ORM\Column(type="string")
     * @Assert\Choice(choices = {"Sportif","Entraineur"})
     */
    protected $profil;

    /**
     * @ORM\Column(type="string", length=255,  nullable=true )
     */
    protected $path;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Choice(choices = {"All","NOT","EC","EU","UC","E","C","U"})
     */
    protected $type_notification;

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

    public function __construct() {
        parent::__construct();
    }

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getDateNaissance() {
        return $this->dateNaissance;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function getSexe() {
        return $this->sexe;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function getTel() {
        return $this->tel;
    }

    public function getProfil() {
        return $this->profil;
    }

    public function getFile() {
        return $this->file;
    }

    /**
     * Set type_notification
     *
     * @param string $typeNotification
     * @return User
     */
    public function setTypeNotification($typeNotification) {
        $this->type_notification = $typeNotification;

        return $this;
    }

    /**
     * Get type_notification
     *
     * @return string 
     */
    public function getTypeNotification() {
        return $this->type_notification;
    }

    public function getPath() {
        return $this->path;
    }

    public function getLat() {
        return $this->lat;
    }

    public function getLng() {
        return $this->lng;
    }

    public function getEvenements() {
        return $this->evenements;
    }

    public function getParticipations() {
        return $this->participations;
    }

    public function getNotification_entraineur() {
        return $this->notification_entraineur;
    }

    public function getNotificationseen() {
        return $this->notificationseen;
    }

    public function getClubs() {
        return $this->clubs;
    }

    public function getConversations1() {
        return $this->conversations1;
    }

    public function getConversations2() {
        return $this->conversations2;
    }

    public function getAlbums() {
        return $this->albums;
    }

    public function getReward() {
        return $this->reward;
    }

    public function getAdherent() {
        return $this->adherent;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setDateNaissance($dateNaissance) {
        $this->dateNaissance = $dateNaissance;
    }

    public function setCreatedAt(\DateTime $createdAt) {
        $this->createdAt = $createdAt;
    }

    public function setSexe($sexe) {
        $this->sexe = $sexe;
    }

    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    public function setTel($tel) {
        $this->tel = $tel;
    }

    public function setProfil($profil) {
        $this->profil = $profil;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function setLat($lat) {
        $this->lat = $lat;
    }

    public function setLng($lng) {
        $this->lng = $lng;
    }

    public function setEvenements($evenements) {
        $this->evenements = $evenements;
    }

    public function setParticipations($participations) {
        $this->participations = $participations;
    }

    public function setNotification_entraineur($notification_entraineur) {
        $this->notification_entraineur = $notification_entraineur;
    }

    public function setNotificationseen($notificationseen) {
        $this->notificationseen = $notificationseen;
    }

    public function setClubs($clubs) {
        $this->clubs = $clubs;
    }

    public function setAlbums($albums) {
        $this->albums = $albums;
    }

    public function setReward($reward) {
        $this->reward = $reward;
    }

    public function setAdherent($adherent) {
        $this->adherent = $adherent;
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


    /**
     * Add evenements
     *
     * @param \FrontOffice\OptimusBundle\Entity\Event $evenements
     * @return User
     */
    public function addEvenement(\FrontOffice\OptimusBundle\Entity\Event $evenements)
    {
        $this->evenements[] = $evenements;
    
        return $this;
    }

    /**
     * Remove evenements
     *
     * @param \FrontOffice\OptimusBundle\Entity\Event $evenements
     */
    public function removeEvenement(\FrontOffice\OptimusBundle\Entity\Event $evenements)
    {
        $this->evenements->removeElement($evenements);
    }

    /**
     * Add participations
     *
     * @param \FrontOffice\OptimusBundle\Entity\Participation $participations
     * @return User
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
     * Add notification_entraineur
     *
     * @param \FrontOffice\OptimusBundle\Entity\Notification $notificationEntraineur
     * @return User
     */
    public function addNotificationEntraineur(\FrontOffice\OptimusBundle\Entity\Notification $notificationEntraineur)
    {
        $this->notification_entraineur[] = $notificationEntraineur;
    
        return $this;
    }

    /**
     * Remove notification_entraineur
     *
     * @param \FrontOffice\OptimusBundle\Entity\Notification $notificationEntraineur
     */
    public function removeNotificationEntraineur(\FrontOffice\OptimusBundle\Entity\Notification $notificationEntraineur)
    {
        $this->notification_entraineur->removeElement($notificationEntraineur);
    }

    /**
     * Get notification_entraineur
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotificationEntraineur()
    {
        return $this->notification_entraineur;
    }

    /**
     * Add notificationseen
     *
     * @param \FrontOffice\OptimusBundle\Entity\NotificationSeen $notificationseen
     * @return User
     */
    public function addNotificationseen(\FrontOffice\OptimusBundle\Entity\NotificationSeen $notificationseen)
    {
        $this->notificationseen[] = $notificationseen;
    
        return $this;
    }

    /**
     * Remove notificationseen
     *
     * @param \FrontOffice\OptimusBundle\Entity\NotificationSeen $notificationseen
     */
    public function removeNotificationseen(\FrontOffice\OptimusBundle\Entity\NotificationSeen $notificationseen)
    {
        $this->notificationseen->removeElement($notificationseen);
    }

    /**
     * Add clubs
     *
     * @param \FrontOffice\OptimusBundle\Entity\Club $clubs
     * @return User
     */
    public function addClub(\FrontOffice\OptimusBundle\Entity\Club $clubs)
    {
        $this->clubs[] = $clubs;
    
        return $this;
    }

    /**
     * Remove clubs
     *
     * @param \FrontOffice\OptimusBundle\Entity\Club $clubs
     */
    public function removeClub(\FrontOffice\OptimusBundle\Entity\Club $clubs)
    {
        $this->clubs->removeElement($clubs);
    }

    /**
     * Add conversations1
     *
     * @param \FrontOffice\OptimusBundle\Entity\Conversation $conversations1
     * @return User
     */
    public function addConversations1(\FrontOffice\OptimusBundle\Entity\Conversation $conversations1)
    {
        $this->conversations1[] = $conversations1;
    
        return $this;
    }

    /**
     * Remove conversations1
     *
     * @param \FrontOffice\OptimusBundle\Entity\Conversation $conversations1
     */
    public function removeConversations1(\FrontOffice\OptimusBundle\Entity\Conversation $conversations1)
    {
        $this->conversations1->removeElement($conversations1);
    }

    /**
     * Add conversations2
     *
     * @param \FrontOffice\OptimusBundle\Entity\Conversation $conversations2
     * @return User
     */
    public function addConversations2(\FrontOffice\OptimusBundle\Entity\Conversation $conversations2)
    {
        $this->conversations2[] = $conversations2;
    
        return $this;
    }

    /**
     * Remove conversations2
     *
     * @param \FrontOffice\OptimusBundle\Entity\Conversation $conversations2
     */
    public function removeConversations2(\FrontOffice\OptimusBundle\Entity\Conversation $conversations2)
    {
        $this->conversations2->removeElement($conversations2);
    }

    /**
     * Add albums
     *
     * @param \FrontOffice\OptimusBundle\Entity\Album $albums
     * @return User
     */
    public function addAlbum(\FrontOffice\OptimusBundle\Entity\Album $albums)
    {
        $this->albums[] = $albums;
    
        return $this;
    }

    /**
     * Remove albums
     *
     * @param \FrontOffice\OptimusBundle\Entity\Album $albums
     */
    public function removeAlbum(\FrontOffice\OptimusBundle\Entity\Album $albums)
    {
        $this->albums->removeElement($albums);
    }

    /**
     * Add reward
     *
     * @param \FrontOffice\OptimusBundle\Entity\Reward $reward
     * @return User
     */
    public function addReward(\FrontOffice\OptimusBundle\Entity\Reward $reward)
    {
        $this->reward[] = $reward;
    
        return $this;
    }

    /**
     * Remove reward
     *
     * @param \FrontOffice\OptimusBundle\Entity\Reward $reward
     */
    public function removeReward(\FrontOffice\OptimusBundle\Entity\Reward $reward)
    {
        $this->reward->removeElement($reward);
    }

    /**
     * Add adherent
     *
     * @param \FrontOffice\OptimusBundle\Entity\Member $adherent
     * @return User
     */
    public function addAdherent(\FrontOffice\OptimusBundle\Entity\Member $adherent)
    {
        $this->adherent[] = $adherent;
    
        return $this;
    }

    /**
     * Remove adherent
     *
     * @param \FrontOffice\OptimusBundle\Entity\Member $adherent
     */
    public function removeAdherent(\FrontOffice\OptimusBundle\Entity\Member $adherent)
    {
        $this->adherent->removeElement($adherent);
    }
}