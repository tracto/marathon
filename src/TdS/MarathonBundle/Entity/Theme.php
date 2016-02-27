<?php

namespace TdS\MarathonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Theme
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TdS\MarathonBundle\Entity\ThemeRepository")
 */
class Theme
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var \Date
     *
     * @ORM\Column(name="dateDebut", type="date")
     * @Assert\Date()
     */
    private $dateDebut;

    /**
     * @var \Date
     *
     * @ORM\Column(name="dateFin", type="date")
     * @Assert\Date()
     */
    private $dateFin;

    /** @ORM\ManyToOne(targetEntity="TdS\MarathonBundle\Entity\Saison",inversedBy="themes")
    * @ORM\JoinColumn(nullable=false)
    */
    private $saison;


    /**
     *
     * @ORM\OneToOne(targetEntity="TdS\MarathonBundle\Entity\Image", cascade={"persist"})
     */
    private $image;


    /**
     * @ORM\ManyToOne(targetEntity="TdS\MarathonBundle\Entity\Joggeur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $joggeur;


    /**
     * @ORM\OneToMany(targetEntity="TdS\MarathonBundle\Entity\MusicTitle",mappedBy="theme")
     * @ORM\OrderBy({"dateUpload" = "ASC"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $musicTitles;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * @var string
     *
     * @ORM\Column(name="chronique", type="text", nullable=true)
     */
    private $chronique;

     /**
     * @ORM\ManyToOne(targetEntity="TdS\MarathonBundle\Entity\Joggeur")
     * @ORM\JoinColumn(nullable=true)
     */
    private $joggeurChronique;
    
     /**
     * @var boolean
     *
     * @ORM\Column(name="draftmode", type="boolean", nullable=true)
     */
     private $draftmode;


    /**
     * @var boolean
     *
     * @ORM\Column(name="activate", type="boolean", nullable=true)
     */
    private $activate;

    
    /**
     * @var boolean
     *
     * @ORM\Column(name="postActivate", type="boolean", nullable=true)
     */
    private $postActivate;


    public function __construct(){
        // $this->$musicTitles = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Theme
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set dateDebut
     *
     * @param \Date $dateDebut
     *
     * @return Theme
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \Date
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \Date $dateFin
     *
     * @return Theme
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \Date
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }




    public function setSaison(Saison $saison)
    {
        $this->saison = $saison;
        return $this;
    }

    public function getSaison()
    {
        return $this->saison;
    }


    /**
     * Set image
     *
     * @param string $image
     *
     * @return Theme
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }


    /**
     * Set joggeur
     *
     * @param string $joggeur
     *
     * @return Theme
     */
    public function setJoggeur($joggeur)
    {
        $this->joggeur = $joggeur;

        return $this;
    }

    /**
     * Get joggeur
     *
     * @return string
     */
    public function getJoggeur()
    {
        return $this->joggeur;
    }


    public function addMusicTitle(MusicTitle $musicTitle)
    {
        $this->musicTitles[] = $musicTitle;

        return $this;
    }

    public function removeMusicTitle(MusicTitle $musicTitle)
    {
        $this->musicTitles->removeElement($musicTitle);
    }

    public function getMusicTitles()
    {
        return $this->musicTitles;
    }


    /**
     * Set description
     *
     * @param string $description
     *
     * @return Theme
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set chronique
     *
     * @param string $chronique
     *
     * @return Theme
     */
    public function setChronique($chronique)
    {
        $this->chronique = $chronique;

        return $this;
    }

    /**
     * Get chronique
     *
     * @return string
     */
    public function getChronique()
    {
        return $this->chronique;
    }

    /**
     * Set joggeurChronique
     *
     * @param string $joggeurChronique
     *
     * @return Theme
     */
    public function setJoggeurChronique($joggeurChronique)
    {
        $this->joggeurChronique = $joggeurChronique;

        return $this;
    }

    /**
     * Get joggeurChronique
     *
     * @return string
     */
    public function getJoggeurChronique()
    {
        return $this->joggeurChronique;
    }

    /**
     * Set draftmode
     *
     * @param boolean $draftmode
     *
     * @return Theme
     */
    public function setDraftmode($draftmode)
    {
        $this->draftmode = $draftmode;

        return $this;
    }

    /**
     * Get draftmode
     *
     * @return boolean
     */
    public function getDraftmode()
    {
        return $this->draftmode;
    }


    /**
     * Set activate
     *
     * @param boolean $activate
     *
     * @return Theme
     */
    public function setActivate($activate)
    {
        $this->activate = $activate;

        return $this;
    }

    /**
     * Get activate
     *
     * @return boolean
     */
    public function getActivate()
    {
        return $this->activate;
    }

    /**
     * Set postActivate
     *
     * @param boolean $postActivate
     *
     * @return Theme
     */
    public function setPostActivate($postActivate)
    {
        $this->postActivate = $postActivate;

        return $this;
    }

    /**
     * Get postActivate
     *
     * @return boolean
     */
    public function getPostActivate()
    {
        return $this->postActivate;
    }


    public function getTimeGap($dateFin){
        $today=new \DateTime('now');
        $gap = $today->diff($dateFin);
        return $gap->format('%R%a');
    }
}

