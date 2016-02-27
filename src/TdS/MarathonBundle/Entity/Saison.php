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
     * @var boolean
     *
     * @ORM\Column(name="activate", type="boolean")
     */
    private $activate;

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
     * Set activate
     *
     * @param boolean $activate
     *
     * @return Saison
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

