<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Photo
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\PhotoRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="photo")
 */
class Photo 
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;
    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;
    /**
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User", inversedBy="photos")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true ,onDelete="CASCADE")
     **/
   
   protected $user;
    /**
     * @ORM\ManyToOne(targetEntity="Album", inversedBy="images")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id", nullable=true ,onDelete="CASCADE")
     **/
   
   protected $album;
   
   /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="images")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=true ,onDelete="CASCADE")
     **/
   
   protected $event;
   /**
     * @ORM\ManyToOne(targetEntity="Competition", inversedBy="imagesCompetition")
     * @ORM\JoinColumn(name="competition_id", referencedColumnName="id", nullable=true ,onDelete="CASCADE")
     **/
   
   protected $competition;
   
    public function __construct()
    {
        
      
//        $this->dateCreation = new \DateTime();
        

        // your own logic
    }
    public function getId() {
        return $this->id;
    }

   

    public function getAlbum() {
        return $this->album;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getPath() {
        return $this->path;
    }

    public function getFile() {
        return $this->file;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    
    public function setAlbum($album) {
        $this->album = $album;
    }
    
    public function getEvent() {
        return $this->event;
    }
    public function setEvent($event) {
        $this->event = $event;
    }
       public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'upload/albumPhoto/';
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        $this->tempFile = $this->getWebPath();
        $this->oldFile  = $this->getPath();
        if (null !== $this->file) {
            // faites ce que vous voulez pour générer un nom unique
            $this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
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
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Set user
     *
     * @param \FrontOffice\UserBundle\Entity\User $user
     * @return Photo
     */
    public function setUser(\FrontOffice\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \FrontOffice\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set competition
     *
     * @param \FrontOffice\OptimusBundle\Entity\Competition $competition
     * @return Photo
     */
    public function setCompetition(\FrontOffice\OptimusBundle\Entity\Competition $competition = null)
    {
        $this->competition = $competition;
    
        return $this;
    }

    /**
     * Get competition
     *
     * @return \FrontOffice\OptimusBundle\Entity\Competition 
     */
    public function getCompetition()
    {
        return $this->competition;
    }
}