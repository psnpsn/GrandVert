<?php

namespace JardinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="jardin")
 */
class Jardin
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_creation", type="date")
     */
    private $dateC;

    /**
     * @ORM\OneToMany(targetEntity="Plantation", mappedBy="jardinId")
     */
    private $plantations;

    /**
     * @ORM\OneToMany(targetEntity="Note", mappedBy="jardinId")
     */
    private $notes;


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
     * Set userId
     *
     * @param integer $userId
     *
     * @return Jardin
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Jardin
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
     * Set dateC
     *
     * @param \DateTime $dateC
     *
     * @return Jardin
     */
    public function setDateC($dateC)
    {
        $this->dateC = $dateC;
    
        return $this;
    }

    /**
     * Get dateC
     *
     * @return \DateTime
     */
    public function getDateC()
    {
        return $this->dateC;
    }

    /**
     * @return mixed
     */
    public function getPlantations()
    {
        return $this->plantations;
    }

    /**
     * @param mixed $plantations
     */
    public function setPlantations($plantations)
    {
        $this->plantations = $plantations;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }


}

