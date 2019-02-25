<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ForumBundle;
use AppBundle;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Reponse
 *
 * @ORM\Table(name="reponse")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\ReponseRepository")
 */
class Reponse
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
     * @ORM\Column(name="reponse_original", type="string", length=9999)
     */
    private $reponseOriginal;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse_edited", type="string", length=9999)
     */
    private $reponseEdited;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_roriginal", type="date")
     */
    private $dateRoriginal;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_redited", type="date")
     */
    private $dateRedited;

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
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Sujet")
     * @ORM\JoinColumn(referencedColumnName="id" , onDelete="CASCADE")
     */
    private $Sujet;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id" , onDelete="CASCADE")
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
     * Set reponseOriginal
     *
     * @param string $reponseOriginal
     *
     * @return Reponse
     */
    public function setReponseOriginal($reponseOriginal)
    {
        $this->reponseOriginal = $reponseOriginal;

        return $this;
    }

    /**
     * Get reponseOriginal
     *
     * @return string
     */
    public function getReponseOriginal()
    {
        return $this->reponseOriginal;
    }

    /**
     * Set reponseEdited
     *
     * @param string $reponseEdited
     *
     * @return Reponse
     */
    public function setReponseEdited($reponseEdited)
    {
        $this->reponseEdited = $reponseEdited;

        return $this;
    }

    /**
     * Get reponseEdited
     *
     * @return string
     */
    public function getReponseEdited()
    {
        return $this->reponseEdited;
    }

    /**
     * Set dateRoriginal
     *
     * @param \DateTime $dateRoriginal
     *
     * @return Reponse
     */
    public function setDateRoriginal($dateRoriginal)
    {
        $this->dateRoriginal = $dateRoriginal;

        return $this;
    }

    /**
     * Get dateRoriginal
     *
     * @return \DateTime
     */
    public function getDateRoriginal()
    {
        return $this->dateRoriginal;
    }

    /**
     * Set dateRedited
     *
     * @param \DateTime $dateRedited
     *
     * @return Reponse
     */
    public function setDateRedited($dateRedited)
    {
        $this->dateRedited = $dateRedited;

        return $this;
    }

    /**
     * Get dateRedited
     *
     * @return \DateTime
     */
    public function getDateRedited()
    {
        return $this->dateRedited;
    }

    /**
     * Set sujet
     *
     * @param \ForumBundle\Entity\Sujet $sujet
     *
     * @return Reponse
     */
    public function setSujet(\ForumBundle\Entity\Sujet $sujet = null)
    {
        $this->Sujet = $sujet;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return \ForumBundle\Entity\Sujet
     */
    public function getSujet()
    {
        return $this->Sujet;
    }


    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Reponse
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
     * Set archive
     *
     * @param boolean $archive
     *
     * @return Reponse
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
     * @return Reponse
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
}
