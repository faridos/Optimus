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
class Seance {

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
     * @ORM\JoinColumn(name="program_id", referencedColumnName="id" ,onDelete="CASCADE")
     * */
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="Date_debut", type="string", length=255)
     */
    private $datedebut;

    /**
     * @var string
     *
     * @ORM\Column(name="Date_fin", type="string",  length=255)
     * 
     */
    private $datefin;

    public function __construct() {
        
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

    public function setId($id) {
        $this->id = $id;
    }

    public function setProgram($program) {
        $this->program = $program;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

   
    public function getDatedebut() {
        return $this->datedebut;
    }

    public function getDatefin() {
        return $this->datefin;
    }

    public function setDatedebut($datedebut) {
        $this->datedebut = $datedebut;
    }

    public function setDatefin($datefin) {
        $this->datefin = $datefin;
    }

      
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

        //put your code here
}