<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ForumBundle;
use AppBundle;
use PlanteBundle;

/**
 * Sujet
 *
 * @ORM\Table(name="sujet")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\SujetRepository")
 */
class Sujet
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
     * @ORM\Column(name="sujet_original", type="string", length=9999)
     */
    private $sujetOriginal;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet_edited", type="string", length=9999)
     */
    private $sujetEdited;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_original", type="date")
     */
    private $dateOriginal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_edited", type="date")
     */
    private $dateEdited;

    /**
     * @var int
     *
     * @ORM\Column(name="nbshare", type="integer")
     */
    private $nbshare;

    /**
     * @var int
     *
     * @ORM\Column(name="nbviews", type="integer")
     */
    private $nbviews;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;


    /**
     * @ORM\ManyToOne(targetEntity="PlanteBundle\Entity\plante")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $Plante;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $User;


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
     * Set sujetOriginal
     *
     * @param string $sujetOriginal
     *
     * @return Sujet
     */
    public function setSujetOriginal($sujetOriginal)
    {
        $this->sujetOriginal = $sujetOriginal;

        return $this;
    }

    /**
     * Get sujetOriginal
     *
     * @return string
     */
    public function getSujetOriginal()
    {
        return $this->sujetOriginal;
    }

    /**
     * Set sujetEdited
     *
     * @param string $sujetEdited
     *
     * @return Sujet
     */
    public function setSujetEdited($sujetEdited)
    {
        $this->sujetEdited = $sujetEdited;

        return $this;
    }

    /**
     * Get sujetEdited
     *
     * @return string
     */
    public function getSujetEdited()
    {
        return $this->sujetEdited;
    }

    /**
     * Set dateOriginal
     *
     * @param \DateTime $dateOriginal
     *
     * @return Sujet
     */
    public function setDateOriginal($dateOriginal)
    {
        $this->dateOriginal = $dateOriginal;

        return $this;
    }

    /**
     * Get dateOriginal
     *
     * @return \DateTime
     */
    public function getDateOriginal()
    {
        return $this->dateOriginal;
    }

    /**
     * Set dateEdited
     *
     * @param \DateTime $dateEdited
     *
     * @return Sujet
     */
    public function setDateEdited($dateEdited)
    {
        $this->dateEdited = $dateEdited;

        return $this;
    }

    /**
     * Get dateEdited
     *
     * @return \DateTime
     */
    public function getDateEdited()
    {
        return $this->dateEdited;
    }

    /**
     * Set nbshare
     *
     * @param integer $nbshare
     *
     * @return Sujet
     */
    public function setNbshare($nbshare)
    {
        $this->nbshare = $nbshare;

        return $this;
    }

    /**
     * Get nbshare
     *
     * @return int
     */
    public function getNbshare()
    {
        return $this->nbshare;
    }

    /**
     * Set nbviews
     *
     * @param integer $nbviews
     *
     * @return Sujet
     */
    public function setNbviews($nbviews)
    {
        $this->nbviews = $nbviews;

        return $this;
    }

    /**
     * Get nbviews
     *
     * @return int
     */
    public function getNbviews()
    {
        return $this->nbviews;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return Sujet
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set plante
     *
     * @param integer $plante
     *
     * @return Sujet
     */
    public function setPlante($plante)
    {
        $this->plante = $plante;

        return $this;
    }

    /**
     * Get plante
     *
     * @return integer
     */
    public function getPlante()
    {
        return $this->plante;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Sujet
     */
    public function setUser($user)
    {
        $this->User = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * Set idPlante
     *
     * @param integer $idPlante
     *
     * @return Sujet
     */
    public function setIdPlante($idPlante)
    {
        $this->id_plante = $idPlante;

        return $this;
    }

    /**
     * Get idPlante
     *
     * @return integer
     */
    public function getIdPlante()
    {
        return $this->id_plante;
    }
}
