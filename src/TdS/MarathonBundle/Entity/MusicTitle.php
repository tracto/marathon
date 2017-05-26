<?php

namespace TdS\MarathonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use TdS\MarathonBundle\MP3File\TdSMP3File;
/**
 * MusicTitle
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TdS\MarathonBundle\Entity\MusicTitleRepository")
  * @ORM\HasLifecycleCallbacks
 */
class MusicTitle
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpload", type="datetime")
     * @Assert\DateTime()
     */
    private $dateUpload;


    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=255, nullable=true)
     */
    private $duration;


   /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @ORM\ManyToOne(targetEntity="TdS\MarathonBundle\Entity\Joggeur",inversedBy="musicTitles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $joggeur;


    /**
     * @ORM\ManyToOne(targetEntity="TdS\MarathonBundle\Entity\Theme",inversedBy="musicTitles")
     * @ORM\JoinColumn(nullable=false)
     */

    private $theme;


    /**
    * @Assert\File(
    *     maxSize = "2000000",
    *     mimeTypes = {"audio/mpeg", "audio/mp3"},
    *     mimeTypesMessage = "Tu n'as pas uploadé un fichier .mp3 valide"
    * )
    */
    private $file;

    private $tempFilename;


    public function __construct(){
        $this->dateUpload = new \DateTime();
    }


  
    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file)
    {
        $this->file = $file;

        if(null !== $this->url){
            $this->tempFilename=$this->url;
            $this->url=null;
            $this->alt=null;
        }
    }

    /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    public function preUpload(){
        if(null===$this->file){
            return;
        }

        $this->url="mp3";

        $this->alt=$this->file->getClientOriginalName();
        
        $mp3File =new TdSMP3File($this->file);
        $mp3Duration=$mp3File->getDuration();
        $this->setDuration($mp3Duration);
    }


    /**
    * @ORM\PostPersist()
    * @ORM\PostUpdate()
    */
    public function upload(){
        if(null===$this->file){
            return;
        }

        if(null!==$this->tempFilename){
            $oldFile=$this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
            if(file_exists($oldFile)){
                unlink($oldFile);
            }
        }

        $this->file->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->url
        );       

    }


    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload(){
        $this->tempFilename=$this->getUploadRootDir().'/'.$this->id.'.'.$this->url;

        
        
    }


    /**
     * @ORM\PostRemove()
     */
    public function removeUpload(){
        if(file_exists($this->tempFilename)){
            unlink($this->tempFilename);
        }
    }

    public function getUploadDir(){
        return 'uploads/music';
    }

    public function getUploadRootDir(){
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }



    public function getWebPath(){
        return $this->getUploadDir().'/'.$this->getId().'.'.$this->getUrl();
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
     * @return MusicTitle
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
     * Set dateUpload
     *
     * @param \DateTime $dateUpload
     *
     * @return MusicTitle
     */
    public function setDateUpload($dateUpload)
    {
        $this->dateUpload = $dateUpload;

        return $this;
    }

    /**
     * Get dateUpload
     *
     * @return \DateTime
     */
    public function getDateUpload()
    {
        return $this->dateUpload;
    }

    /**
     * Set duration
     *
     * @param string $duration
     *
     * @return MusicTitle
     */
    public function setDuration($duration)
    {   
        
         $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string
     */
    public function getDuration()
    {
        
        return $this->duration;
    }

    /**
     * Set src
     *
     * @param string $src
     *
     * @return MusicTitle
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * Get src
     *
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }


    public function setJoggeur(Joggeur $joggeur)
    {
        $this->joggeur = $joggeur;
        return $this;
    }

    public function getJoggeur()
    {
        return $this->joggeur;
    }

    public function setTheme(Theme $theme)
    {
        $this->theme = $theme;
        return $this;
    }

    public function getTheme()
    {
        return $this->theme;
    }


    /**
     * Set url
     *
     * @param string $url
     *
     * @return MusicTitle
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return MusicTitle
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

}
