<?php

namespace JardinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private $plantId;

    /**
     * @ORM\ManyToOne(targetEntity="Jardin", inversedBy="plantations")
     * @ORM\JoinColumn(name="jardin_id", referencedColumnName="id")
     */
    private $jardinId;

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
     * @ORM\Column(name="days_to_harvest", type="integer")
     */
    private $daysToHarvest;


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
    public function getPlantId()
    {
        return $this->plantId;
    }

    /**
     * @param mixed $plantId
     */
    public function setPlantId($plantId)
    {
        $this->plantId = $plantId;
    }

    /**
     * @return mixed
     */
    public function getJardinId()
    {
        return $this->jardinId;
    }

    /**
     * @param mixed $jardinId
     */
    public function setJardinId($jardinId)
    {
        $this->jardinId = $jardinId;
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
    public function getDaysToHarvest()
    {
        return $this->daysToHarvest;
    }

    /**
     * @param string $daysToHarvest
     */
    public function setDaysToHarvest($daysToHarvest)
    {
        $this->daysToHarvest = $daysToHarvest;
    }


}

