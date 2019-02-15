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
     * @ORM\Column(name="fos_user_id", type="integer")
     * @var int
     */
    private $userId;

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
    public function setuserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getuserId()
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
}

