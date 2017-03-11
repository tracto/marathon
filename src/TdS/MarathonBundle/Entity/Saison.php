<?php

namespace TdS\MarathonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Saison
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TdS\MarathonBundle\Entity\SaisonRepository")
 */
class Saison
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
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;


    /**
     * @var integer
     *
     * @ORM\Column(name="statut", type="integer")
     */
    private $statut;

    /**
     *
     * @ORM\OneToOne(targetEntity="TdS\MarathonBundle\Entity\Image", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="bilan", type="text", nullable=true)
     */
    private $bilan;


    /**
     * @ORM\OneToMany(targetEntity="TdS\MarathonBundle\Entity\Theme", mappedBy="saison")
     * @ORM\OrderBy({"dateFin" = "DESC"})
     */
    private $themes;


    public function __construct(){    
        $this->themes = new ArrayCollection();
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
     * @return Saison
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
     * Set type
     *
     * @param boolean $type
     *
     * @return Saison
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return boolean
     */
    public function getType()
    {
        return $this->type;
    }



    /**
     * Set statut
     *
     * @param boolean $statut
     *
     * @return Saison
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return boolean
     */
    public function getStatut()
    {
        return $this->statut;
    }


    /**
     * Set image
     *
     * @param string $image
     *
     * @return Saison
     */
    public function setImage(Image $image=null)
    {
        $this->image = $image;

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
     * Set bilan
     *
     * @param string $bilan
     *
     * @return Saison
     */
    public function setBilan($bilan)
    {
        $this->bilan = $bilan;

        return $this;
    }

    /**
     * Get bilan
     *
     * @return string
     */
    public function getBilan()
    {
        return $this->bilan;
    }
    

    public function getThemes() {
      return $this->themes;
    }

     public function addTheme(Theme $theme)
    {
        $this->themes[] = $theme;
    }

    public function removeTheme(Theme $theme)
    {
        $this->themes->removeElement($theme);
    }
}

