<?php

namespace JardinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PlanteBundle;


/**
 * Plantation
 *
 * @ORM\Table(name="plantation")
 * @ORM\Entity(repositoryClass="JardinBundle\Repository\PlantationRepository")
 */
class Plantation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PlanteBundle\Entity\plante")
     * @ORM\JoinColumn(name="plante_id", referencedColumnName="id")
     */
    private $plante;

    /**
     * @ORM\ManyToOne(targetEntity="Jardin", inversedBy="plantations")
     * @ORM\JoinColumn(name="jardin_id", referencedColumnName="id")
     */
    private $jardin;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_plantation", type="date")
     */
    private $dateP;

    /**
     * @var string
     * @ORM\Column(name="type_plantation", type="string", length=5)
     */
    private $typeP;

    /**
     * @var string
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;


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
     * @return mixed
     */
    public function getPlante()
    {
        return $this->plante;
    }

    /**
     * @param mixed $plante
     */
    public function setPlante($plante)
    {
        $this->plante = $plante;
    }

    /**
     * @return mixed
     */
    public function getJardin()
    {
        return $this->jardin;
    }

    /**
     * @param mixed $jardin
     */
    public function setJardin($jardin)
    {
        $this->jardin = $jardin;
    }

    /**
     * @return \DateTime
     */
    public function getDateP()
    {
        return $this->dateP;
    }

    /**
     * @param \DateTime $dateP
     */
    public function setDateP($dateP)
    {
        $this->dateP = $dateP;
    }

    /**
     * @return string
     */
    public function getTypeP()
    {
        return $this->typeP;
    }

    /**
     * @param string $typeP
     */
    public function setTypeP($typeP)
    {
        $this->typeP = $typeP;
    }

    /**
     * @return string
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @param string $quantite
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;
    }


}

