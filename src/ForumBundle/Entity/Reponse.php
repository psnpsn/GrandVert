<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ForumBundle;
use AppBundle;

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
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Sujet")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $Sujet;

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
     * @param \ForumBundle\Entity\User $user
     *
     * @return Reponse
     */
    public function setUser(\ForumBundle\Entity\User $user = null)
    {
        $this->User = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ForumBundle\Entity\User
     */
    public function getUser()
    {
        return $this->User;
    }
}
