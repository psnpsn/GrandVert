<?php

namespace PreservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EspaceDePreservation
 *
 * @ORM\Table(name="espace_de_preservation")
 * @ORM\Entity(repositoryClass="PreservationBundle\Repository\EspaceDePreservationRepository")
 */
class EspaceDePreservation
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
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 10,
     *      minMessage = "La Longeur Du Nom Du L'espace De Préservation Doit être Supérieure à {{ limit }}",
     *      maxMessage = "La Longeur Du Nom Du L'espace De Préservation Doit être Inférieure à {{ limit }}"
     * )
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="Capacity", type="integer")
     * @Assert\NotBlank
     * @Assert\GreaterThanOrEqual(1,message="La Capacity Du L'espace De Préservation Doit être Supérieure à 1")
     */

    private $capacity;

    /**
     * @var string
     *
     * @ORM\Column(name="Lieu", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "La Longeur Du Lieu Du L'espace De Préservation Doit être Supérieure à {{ limit }}",
     *      maxMessage = "La Longeur Du Lieu Du L'espace De Préservation Doit être Inférieure à {{ limit }}"
     * )
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="EspaceDePreservations")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $user;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return EspaceDePreservation
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     *
     * @return EspaceDePreservation
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return int
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return EspaceDePreservation
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }
}

