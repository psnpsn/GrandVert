<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ForumBundle;
use AppBundle;
use PlanteBundle;
use Symfony\Component\Validator\Constraints as Assert;
use SBC\NotificationsBundle\Builder\NotificationBuilder;
use SBC\NotificationsBundle\Model\NotifiableInterface;

/**
 * Sujet
 *
 * @ORM\Table(name="sujet")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\SujetRepository")
 */
class Sujet implements NotifiableInterface, \JsonSerializable
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
     * @Assert\NotBlank
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
    private $nbshare=0;

    /**
     * @var int
     *
     * @ORM\Column(name="nbviews", type="integer")
     */
    private $nbviews=0;

    /**
     * @var string
     *
     * @ORM\Column(name="open", type="string", length=255)
     */
    private $open="true";

    /**
     * @var string
     *
     * @ORM\Column(name="resolu", type="string", length=255)
     */
    private $resolu="false";

    /**
     * @var string
     *
     * @ORM\Column(name="archive", type="boolean", length=255)
     */
    private $archive=false;

    /**
     * @var int
     *
     * @ORM\Column(name="nbsignal", type="integer")
     */
    private $nbsignal=0;


    /**
     * @ORM\ManyToOne(targetEntity="PlanteBundle\Entity\plante")
     * @ORM\JoinColumn(referencedColumnName="id" , onDelete="CASCADE")
     */
    private $Plante;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id" , onDelete="CASCADE")
     */
    private $User;


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
     * @return integer
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
     * @return integer
     */
    public function getNbviews()
    {
        return $this->nbviews;
    }


    /**
     * Set plante
     *
     * @param \PlanteBundle\Entity\plante $plante
     *
     * @return Sujet
     */
    public function setPlante(\PlanteBundle\Entity\plante $plante = null)
    {
        $this->Plante = $plante;

        return $this;
    }

    /**
     * Get plante
     *
     * @return \PlanteBundle\Entity\plante
     */
    public function getPlante()
    {
        return $this->Plante;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Sujet
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->User = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->User;
    }

    /**
     * Set open
     *
     * @param string $open
     *
     * @return Sujet
     */
    public function setOpen($open)
    {
        $this->open = $open;

        return $this;
    }

    /**
     * Get open
     *
     * @return string
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * Set resolu
     *
     * @param string $resolu
     *
     * @return Sujet
     */
    public function setResolu($resolu)
    {
        $this->resolu = $resolu;

        return $this;
    }

    /**
     * Get resolu
     *
     * @return string
     */
    public function getResolu()
    {
        return $this->resolu;
    }

    /**
     * Set archive
     *
     * @param boolean $archive
     *
     * @return Sujet
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive
     *
     * @return boolean
     */
    public function getArchive()
    {
        return $this->archive;
    }

    /**
     * Set nbsignal
     *
     * @param integer $nbsignal
     *
     * @return Sujet
     */
    public function setNbsignal($nbsignal)
    {
        $this->nbsignal = $nbsignal;

        return $this;
    }

    /**
     * Get nbsignal
     *
     * @return integer
     */
    public function getNbsignal()
    {
        return $this->nbsignal;
    }

    /**
     * Build notifications on entity creation
     * @param NotificationBuilder $builder
     * @return NotificationBuilder
     */
    public function notificationsOnCreate(NotificationBuilder $builder)
    {
        return $builder;
    }

    /**
     * Build notifications on entity update
     * @param NotificationBuilder $builder
     * @return NotificationBuilder
     */
    public function notificationsOnUpdate(NotificationBuilder $builder)
    {
        return $builder;
    }

    /**
     * Build notifications on entity delete
     * @param NotificationBuilder $builder
     * @return NotificationBuilder
     */
    public function notificationsOnDelete(NotificationBuilder $builder)
    {
        return $builder;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }



}
