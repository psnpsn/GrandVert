<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *@Assert\Regex(pattern="/^[a-zAZ ]*$/", message="seulement des caractére")
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom='';
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse='';
    /**
     * @var string
     *@Assert\Regex(pattern="/^[a-zAZ ]*$/", message="seulement des caractére")
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom='';


    /**
     * @var int
     *
     * @ORM\Column(name="tel", type="integer")
     *  @Assert\Length(min = 8, max = 8)
     */
    private $tel;

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }


    /**
     * @var int
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level=0;

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
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score=0;

    /**
     * @var string
     * @ORM\Column(name="avatar", type="string", length=255)
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $avatar='';

    public function __construct()
    {
        parent::__construct();
        // your own logic
        //$this->addRole("ROLE_USER"); // Membre
        // $this->addRole("ROLE_ADMIN"); // Moderateur
        $this->addRole('ROLE_SUPER_ADMIN');  // Admin

    }

    /**
     * Set tel
     *
     * @param integer $tel
     *
     * @return User
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return integer
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return User
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return User
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set daten
     *
     * @param \DateTime $daten
     *
     * @return User
     */
    public function setDaten($daten)
    {
        $this->daten = $daten;

        return $this;
    }

    /**
     * Get daten
     *
     * @return \DateTime
     */
    public function getDaten()
    {
        return $this->daten;
    }

    /**
     * Set datenais
     *
     * @param \DateTime $datenais
     *
     * @return User
     */
    public function setDatenais($datenais)
    {
        $this->datenais = $datenais;

        return $this;
    }

    /**
     * Get datenais
     *
     * @return \DateTime
     */
    public function getDatenais()
    {
        return $this->datenais;
    }

    public function setEmail($email)
    {
        $this->setUsername($email);
        return parent::setEmail($email); // TODO: Change the autogenerated stub
    }

}
