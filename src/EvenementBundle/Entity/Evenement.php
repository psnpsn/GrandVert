<?php
/**
 * Created by PhpStorm.
 * User: Bouazza Med
 * Date: 19/02/2019
 * Time: 15:46
 */

namespace EvenementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Evenement
 * @ORM\Entity(repositoryClass="EvenementBundle\Repository\EvenementRepository")
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $categorie;
    /**
     * @Assert\NotBlank(message="titre?")
     * @ORM\Column(type="string",length=80)
     */
    private $titre;
    /**
     * @ORM\Column(type="string",length=50)
     */
    private $organisation;
    /**
     * @ORM\Column(type="string")
     */
    private $description;
    /**
     * @ORM\Column(type="string")
     * @Assert\File(mimeTypes={ "image/jpeg"})
     */
    private $image;
    /**
     * @ORM\Column(type="string",length=30)
     */
    private $lieu;
    /**
     * @ORM\Column(type="string",length=70)
     */
    private $adresse;
    /**
     * @ORM\Column(type="string",length=30)
     */
    private $gouvernorat;
    /**
     *@ORM\Column(type="datetime")
     */
    private $dated;

    /**
     *@var \DateTime
     *@Assert\Type("DateTime")
     *@Assert\Expression("value >= this.getDated()",message="La date de fin doit être après le début de l'évenement")
     * @ORM\Column(type="datetime")
     */
    private $datef;
    public function __construct()
    {
        $this->dated = new \DateTime('now + 1 day + 1 minute' );
        $this->datef = new \DateTime('now + 1 day + 2 minute' );
    }
    /**
     *@Assert\Type("float")
     *@Assert\Expression("value >= 0")
     * @ORM\Column(type="float")
     */
    private $prix=0;
    /**
     * @ORM\Column(type="boolean")
     */
    private $confa=0;
    /**
     * @ORM\Column(type="integer")
     */
    private $etat=0;
        /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $User;

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
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param mixed $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * @param mixed $lieu
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getGouvernorat()
    {
        return $this->gouvernorat;
    }

    /**
     * @param mixed $gouvernorat
     */
    public function setGouvernorat($gouvernorat)
    {
        $this->gouvernorat = $gouvernorat;
    }

    /**
     * @return mixed
     */
    public function getDated()
    {
        return $this->dated;
    }

    /**
     * @param mixed $dated
     */
    public function setDated($dated)
    {
        $this->dated = $dated;
    }

    /**
     * @return mixed
     */
    public function getDatef()
    {
        return $this->datef;
    }

    /**
     * @param mixed $datef
     */
    public function setDatef($datef)
    {
        $this->datef = $datef;
    }

    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return mixed
     */
    public function getConfa()
    {
        return $this->confa;
    }

    /**
     * @param mixed $confa
     */
    public function setConfa($confa)
    {
        $this->confa = $confa;
    }

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
    public function getUser()
    {
        return $this->User;
    }

    /**
     * @param mixed $User
     */
    public function setUser($User)
    {
        $this->User = $User;
    }



}