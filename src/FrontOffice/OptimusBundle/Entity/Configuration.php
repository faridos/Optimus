<?php

namespace FrontOffice\OptimusBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Configuration
 *
 * @ORM\Table(name="configuration")
 * @ORM\Entity(repositoryClass="FrontOffice\OptimusBundle\Repository\ConfigurationRepository")
 */
class Configuration
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
     * @var float
     *
     * @ORM\Column(name="prixClub", type="float")
     */
    private $prixClub;


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
     * Set prixClub
     *
     * @param float $prixClub
     * @return Configuration
     */
    public function setPrixClub($prixClub)
    {
        $this->prixClub = $prixClub;
    
        return $this;
    }

    /**
     * Get prixClub
     *
     * @return float 
     */
    public function getPrixClub()
    {
        return $this->prixClub;
    }
}