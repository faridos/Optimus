<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\LegacyExecutionContext;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Program
 *
 * @ORM\Table(name="program")
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\ProgramRepository")
 * @Assert\Callback(methods={{ "FrontOffice\OptimusBundle\Validator\Constraints\ContraintValidDateValidator", "isDateValid"}})
 */
class Program {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Club", inversedBy="programs")
     * @ORM\JoinColumn(name="club_id", referencedColumnName="id" ,onDelete="CASCADE")
     * */
    protected $clubp;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255)
     */
    private $nom;
    ///**
     //* @var string
    // *
    // * @ORM\Column(name="description", type="string", length=255)
    // */
//    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_debut", type="datetime")
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Date_fin", type="datetime")
     * 
     */
    private $datefin;

    /**
     * @ORM\OneToMany(targetEntity="Seance", mappedBy="program", cascade={"persist","remove"})
     * */
    protected $seances;



    //Getter
    public function getId() {
        return $this->id;
    }

    public function getClubp() {
        return $this->clubp;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getDatedebut() {
        return $this->datedebut;
    }

    public function getDatefin() {
        return $this->datefin;
    }

    public function getSeances() {
        return $this->seances;
    }

    //Setter
    public function setId($id) {
        $this->id = $id;
    }

    public function setClubp($clubp) {
        $this->clubp = $clubp;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setDatedebut(\DateTime $datedebut) {
        $this->datedebut = $datedebut;
    }

    public function setDatefin(\DateTime $datefin) {
        $this->datefin = $datefin;
    }

    public function setSeances($seances) {
        $this->seances = $seances;
    }
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

        public function __toString() {
        return (string) $this->getId();
    }

    //put your code here
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->seances = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add seances
     *
     * @param \FrontOffice\OptimusBundle\Entity\Seance $seances
     * @return Program
     */
    public function addSeance(\FrontOffice\OptimusBundle\Entity\Seance $seances)
    {
        $this->seances[] = $seances;
    
        return $this;
    }

    /**
     * Remove seances
     *
     * @param \FrontOffice\OptimusBundle\Entity\Seance $seances
     */
    public function removeSeance(\FrontOffice\OptimusBundle\Entity\Seance $seances)
    {
        $this->seances->removeElement($seances);
    }
}