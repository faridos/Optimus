<?php

namespace FrontOffice\OptimusBundle\Entity;

/**
 * Description of Comment
 *
 * @author ABWEB
 */
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\CommentRepository")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="comment")
 */
class Comment 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
   private $id;
  
    /**
     * 
     *
     * @var commenteur
     * @ORM\ManyToOne(targetEntity="FrontOffice\UserBundle\Entity\User")
     */
    protected $commenteur;
    /**
     * 
     *
     * @var event
     * @ORM\ManyToOne(targetEntity="FrontOffice\OptimusBundle\Entity\Event", inversedBy="eventcomments")
     */
    protected $event;
    /**
     * 
     *
     * @var  club
     * @ORM\ManyToOne(targetEntity="FrontOffice\OptimusBundle\Entity\Club", inversedBy="clubcomments")
     */
    protected $club;
     
    
   /**
    *@var commentaire
    * @ORM\Column(type="text")
    */
    
    private $Commentaire;
    
    /**
     *@var createdat
     * @ORM\Column(type="datetime")
     */
    private $createdat;
 

    
    /**
     * @ORM\PrePersist
     */
    
    public function creationDate() {
        
        $this->setCreatedat(new \Datetime());
       
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
     * Set Commentaire
     *
     * @param string $commentaire
     * @return Comment
     */
    public function setCommentaire($commentaire)
    {
        $this->Commentaire = $commentaire;

        return $this;
    }

    /**
     * Get Commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->Commentaire;
    }

    /**
     * Set createdat
     *
     * @param \DateTime $createdat
     * @return Comment
     */
    public function setCreatedat($createdat)
    {
        $this->createdat = $createdat;

        return $this;
    }

    /**
     * Get createdat
     *
     * @return \DateTime 
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }

    /**
     * Set commenteur
     *
     * @param \FrontOffice\UserBundle\Entity\User $commenteur
     * @return Comment
     */
    public function setCommenteur(\FrontOffice\UserBundle\Entity\User $commenteur = null)
    {
        $this->commenteur = $commenteur;

        return $this;
    }

    /**
     * Get commenteur
     *
     * @return \FrontOffice\UserBundle\Entity\User 
     */
    public function getCommenteur()
    {
        return $this->commenteur;
    }

    
    /**
     * Set event
     *
     * @param \FrontOffice\OptimusBundle\Entity\Event $event
     * @return Comment
     */
    public function setEvent(\FrontOffice\OptimusBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \FrontOffice\OptimusBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set club
     *
     * @param \FrontOffice\OptimusBundle\Entity\Club $club
     * @return Comment
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

     public function getDureeComment() {
        $date = new \DateTime();
        $diff = $date->diff($this->createdat);
        $durée = "";
        if ($diff->d >= 1):
            $durée = "il y'a " . $diff->d . " jours";
        elseif ($diff->h >= 1):
            $durée = "il y'a " . $diff->h . " heur";
        elseif ($diff->i > 1):
            $durée = "il y'a " . $diff->i . " min";
        else:
            $durée = "Maintenant";
        endif;
        return $durée;
    }
   
}
