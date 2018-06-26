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
     * @ORM\OneToOne(targetEntity="TdS\MarathonBundle\Entity\Image", cascade={"persist","remove"})
     * @Assert\Valid()
     */
    private $image;


    /**
     * @ORM\ManyToOne(targetEntity="TdS\MarathonBundle\Entity\Joggeur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $joggeur;


    /**
     * @ORM\OneToMany(targetEntity="TdS\MarathonBundle\Entity\MusicTitle", cascade={"persist","remove"}, mappedBy="theme")
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
     * @var integer
     *
     * @ORM\Column(name="statut", type="integer", nullable=true)
     */
    private $statut;


    /**
     *
     * @ORM\OneToMany(targetEntity="TdS\MarathonBundle\Entity\Score", cascade={"persist","remove"},mappedBy="theme")
     * @ORM\JoinColumn(nullable=true)
     */
    private $scores;


    /**
     *
     * @ORM\OneToOne(targetEntity="TdS\CommentBundle\Entity\Thread", cascade={"persist","remove"})
     */
    private $thread;



     /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=255, nullable=true)
     */
    private $zip;





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
     * Set statut
     *
     * @param integer $statut
     *
     * @return Theme
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get postActivate
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->statut;
    }





    public function getTimeGap($dateFin){
        $today=new \DateTime('now');
        $gap = $today->diff($dateFin);
        return $gap->format('%R%a');
    }


    /**
     * Set thread
     *
     * @param string $thread
     *
     * @return Theme
     */
    public function setThread($thread)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return string
     */
    public function getThread()
    {
        return $this->thread;
    }



    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return Theme
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return integer
     */
    public function getZip()
    {
        return $this->zip;
    }

}

