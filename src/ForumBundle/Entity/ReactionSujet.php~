<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ForumBundle;
use AppBundle;

/**
 * ReactionSujet
 *
 * @ORM\Table(name="reaction_sujet")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\ReactionSujetRepository")
 */
class ReactionSujet
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Sujet", inversedBy="ReactionSujet")
     */
    protected $Sujet;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="ReactionSujet" )
     */
    protected $User;


    /**
     * @var string
     *
     * @ORM\Column(name="reaction", type="string", length=255)
     */
    private $reaction;


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
     * Set reaction
     *
     * @param string $reaction
     *
     * @return ReactionSujet
     */
    public function setReaction($reaction)
    {
        $this->reaction = $reaction;

        return $this;
    }

    /**
     * Get reaction
     *
     * @return string
     */
    public function getReaction()
    {
        return $this->reaction;
    }

    /**
     * Set sujet
     *
     * @param \ForumBundle\Entity\Sujet $sujet
     *
     * @return ReactionSujet
     */
    public function setSujet(\ForumBundle\Entity\Sujet $sujet)
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
     * @return ReactionSujet
     */
    public function setUser(\ForumBundle\Entity\User $user)
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
