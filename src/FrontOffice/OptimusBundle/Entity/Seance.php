<?php
namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Seance
 *
 * @ORM\Table(name="seance")
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\SeanceRepository")
 */
class Seance 
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
     * @ORM\ManyToOne(targetEntity="Program", inversedBy="seances")
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id")
     **/
    protected $program;
    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255)
     */
    private $nom;
    /**
     * @var string
     *
     * @ORM\Column(name="jour", type="string", length=255)
     * @Assert\Choice(choices = {"Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"})
     */
    private $jourseance;
    /**
     * @var datetime $heuredebut
     *
     * @ORM\Column(name="heure_debut", type="time", nullable=true)
     */
    private $heuredebut;
    /**
     * @var datetime $heurefin
     *
     * @ORM\Column(name="heure_fin", type="time", nullable=true)
     */
    private $heurefin;
     public function __construct()
    {
      
      

    }
    public function getId() {
        return $this->id;
    }

    public function getProgram() {
        return $this->program;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getJourseance() {
        return $this->jourseance;
    }

    public function getHeuredebut() {
        return $this->heuredebut;
    }

    public function getHeurefin() {
        return $this->heurefin;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setProgram($program) {
        $this->program = $program;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setJourseance($jourseance) {
        $this->jourseance = $jourseance;
    }

    public function setHeuredebut(\DateTime $heuredebut) {
        $this->heuredebut = $heuredebut;
    }

    public function setHeurefin(\DateTime $heurefin) {
        $this->heurefin = $heurefin;
    }

        //put your code here
}
