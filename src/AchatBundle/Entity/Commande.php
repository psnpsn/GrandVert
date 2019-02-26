<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 22/02/2019
 * Time: 22:49
 */

namespace AchatBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class Commande
 * @ORM\Entity
 */

class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     */
    private $contite;
    /**
     * @ORM\Column(type="datetime")
     */
    private $date;
    /**
     * @ORM\Column(type="string")
     */

    private $etat;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="PlanteBundle\Entity\plante")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $plante;

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getContite()
    {
        return $this->contite;
    }

    /**
     * @param mixed $contite
     */
    public function setContite($contite)
    {
        $this->contite = $contite;
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
     * @return mixed
     */
    public function getPlante()
    {
        return $this->plante;
    }

    /**
     * @param mixed $plante
     */
    public function setPlante($plante)
    {
        $this->plante = $plante;
    }

}