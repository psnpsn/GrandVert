<?php
/**
 * Created by PhpStorm.
 * User: Bouazza Med
 * Date: 26/02/2019
 * Time: 17:33
 */

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Participants
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\ParticipantsRepository")
 */
class Participants
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="EvenementBundle\Entity\Evenement", inversedBy="Participants")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $event;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="Participants")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;
    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer")
     */
    private $statut;

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
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
     * @return int
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param int $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

}