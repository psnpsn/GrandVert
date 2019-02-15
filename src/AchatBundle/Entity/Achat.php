<?php
/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 12/02/2019
 * Time: 23:44
 */

namespace AchatBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class achat
 * @ORM\Entity
 */
class Achat
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
     * @ORM\Column(type="float")
     */
    private $total;
}