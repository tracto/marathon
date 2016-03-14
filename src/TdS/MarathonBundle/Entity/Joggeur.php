<?php

namespace TdS\MarathonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Joggeur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TdS\MarathonBundle\Entity\JoggeurRepository")
 */
class Joggeur
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
     * @ORM\Column(name="pseudo", type="string", length=255)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="presentation", type="text")
     */
    private $presentation;

    /**
     *
     * @ORM\OneToOne(targetEntity="TdS\MarathonBundle\Entity\Image", cascade={"persist"})
     */
    private $image;

    /**
     *
     * @ORM\OneToOne(targetEntity="TdS\MarathonBundle\Entity\Website", cascade={"persist"})
     */    
    private $website;


    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @ORM\OneToMany(targetEntity="TdS\MarathonBundle\Entity\MusicTitle", cascade={"persist","remove"},mappedBy="joggeur")
     * @ORM\OrderBy({"dateUpload" = "ASC"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $musicTitles;
    

    


    /**
     * @var integer
     *
     * @ORM\Column(name="pointstogive", type="integer",nullable=true)
     */
     private $pointstogive;




    /**
     *
     * @ORM\OneToOne(targetEntity="TdS\UserBundle\Entity\User", mappedBy="joggeur")     
     */
    private $user;


    


    

    

    public function __construct(){    
        // $this->musicTitles = new ArrayCollection();
       
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
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return Joggeur
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set presentation
     *
     * @param string $presentation
     *
     * @return Joggeur
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Joggeur
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
     * Set image
     *
     * @param string $image
     *
     * @return Joggeur
     */
    public function setWebsite(Website $website=null)
    {
        $this->website = $website;

    }

    /**
     * Get image
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }



    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Joggeur
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
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





    // public function addScore(Score $score)
    // {
    //     $this->scores[] = $score;

    //     return $this;
    // }

    // public function removeScore(Score $score)
    // {
    //     $this->scores->removeElement($score);
    // }

    // public function getScores()
    // {
    //     return $this->scores;
    // }



    


   




    

    /**
     * Set pointstogive
     *
     * @param integer $pointstogive
     *
     * @return Joggeur
     */
    public function setPointstogive($pointstogive)
    {
        $this->pointstogive = $pointstogive;
        return $this;
    }

    /**
     * Get 
     *
     * @return integer
     */
    public function getPointstogive()
    {
        return $this->pointstogive;
    }


     /**
     * Set user
     *
     * @param integer $user
     *
     * @return Joggeur
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }


    

    
}
