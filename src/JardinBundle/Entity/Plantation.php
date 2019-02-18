<?php

namespace JardinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plantation
 *
 * @ORM\Table(name="plantation")
 * @ORM\Entity(repositoryClass="JardinBundle\Repository\PlantationRepository")
 */
class Plantation
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}

