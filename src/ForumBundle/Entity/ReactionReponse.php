<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use ForumBundle;
use AppBundle;

/**
 * ReactionReponse
 *
 * @ORM\Table(name="reaction_reponse")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\ReactionReponseRepository")
 */
class ReactionReponse
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Reponse", inversedBy="ReactionReponse" )
     */
    protected $Reponse;
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="ReactionReponse" )
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
     * @return ReactionReponse
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
     * Set reponse
     *
     * @param \ForumBundle\Entity\Reponse $reponse
     *
     * @return ReactionReponse
     */
    public function setReponse(\ForumBundle\Entity\Reponse $reponse)
    {
        $this->Reponse = $reponse;

        return $this;
    }

    /**
     * Get reponse
     *
     * @return \ForumBundle\Entity\Reponse
     */
    public function getReponse()
    {
        return $this->Reponse;
    }

    /**
     * Set user
     *
     * @param \ForumBundle\Entity\User $user
     *
     * @return ReactionReponse
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
