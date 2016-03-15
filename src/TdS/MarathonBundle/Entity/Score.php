<?php

namespace TdS\MarathonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Score
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TdS\MarathonBundle\Entity\ScoreRepository")
 */
class Score
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
     * @ORM\ManyToOne(targetEntity="TdS\MarathonBundle\Entity\JoggeurScore", inversedBy="scores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $joggeurScore;




    /**
     * @ORM\ManyToOne(targetEntity="TdS\MarathonBundle\Entity\Theme")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theme;   

    /**
     * @var integer
     *
     * @ORM\Column(name="heartpoints", type="integer")
     */
    private $heartpoints;

    /**
     * @var integer
     *
     * @ORM\Column(name="fastpoints", type="integer")
     */
    private $fastpoints;

    private $totalTheme;




    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
     * Set heartpoints
     *
     * @param integer $heartpoints
     *
     * @return Score
     */
    public function setHeartpoints($heartpoints)
    {
        $this->heartpoints = $heartpoints;

        return $this;
    }

    /**
     * Get heartpoints
     *
     * @return integer
     */
    public function getHeartpoints()
    {
        return $this->heartpoints;
    }

    /**
     * Set fastpoints
     *
     * @param integer $fastpoints
     *
     * @return Score
     */
    public function setFastpoints($fastpoints)
    {
        $this->fastpoints = $fastpoints;

        return $this;
    }

    /**
     * Get fastpoints
     *
     * @return integer
     */
    public function getFastpoints()
    {
        return $this->fastpoints;
    }



    /**
     * Get totalTheme
     */
    public function getTotalTheme()
    {
        $this->totalTheme=$this->heartpoints+$this->fastpoints;
        return $this->totalTheme;
    }


    
    /**
     * Set joggeurScore
     *
     * @param integer $joggeurScore
     *
     * @return Score
     */
    public function setJoggeurScore($joggeurScore)
    {
        $this->joggeurScore = $joggeurScore;
        return $this;
    }

    /**
     * Get joggeurScore
     *
     * @return integer
     */
    public function getJoggeurScore()
    {
        return $this->joggeurScore;
    }


}

