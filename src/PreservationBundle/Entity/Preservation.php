<?php

namespace PreservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Preservation
 *
 * @ORM\Table(name="preservation")
 * @ORM\Entity(repositoryClass="PreservationBundle\Repository\PreservationRepository")
 */
class Preservation
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
     * @var \DateTime
     * @ORM\Column(name="DateDebut", type="date")
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual("today",message="La Date De Début De Réservation Doit être Supérieure Ou égale à La Date D'aujourd'hui")
     */
    private $dateDebut;

    /**
     * @var \DateTime
     * @ORM\Column(name="DateFin", type="date")
     * @Assert\NotBlank
     * @Assert\GreaterThan(propertyPath="dateDebut",message="La Date De La Fin De Réservation Doit être Supérieure à La Date De Début")
     */
    private $dateFin;

    /**
     * @var int
     *
     * @ORM\Column(name="NbPlaces", type="integer")
     * @Assert\NotBlank
     * @Assert\Range(
     *      min = 1,
     *      max = 3,
     *      minMessage = "NbPlaces à Réserver Doit être Supérieure à {{ limit }}",
     *      maxMessage = "NbPlaces à Réserver Doit être Inférieure à {{ limit }}"
     * )
     */
    private $nbPlaces;


    /**
     * @ORM\ManyToOne(targetEntity="EspaceDePreservation")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $EspaceDePreservation;

    /**
     * @return mixed
     */
    public function getEspaceDePreservation()
    {
        return $this->EspaceDePreservation;
    }

    /**
     * @param mixed $EspaceDePreservation
     */
    public function setEspaceDePreservation($EspaceDePreservation)
    {
        $this->EspaceDePreservation = $EspaceDePreservation;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="Preservations")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $user;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Preservation
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Preservation
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * Set nbPlaces
     *
     * @param integer $nbPlaces
     *
     * @return Preservation
     */
    public function setNbPlaces($nbPlaces)
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    /**
     * Get nbPlaces
     *
     * @return int
     */
    public function getNbPlaces()
    {
        return $this->nbPlaces;
    }
}

