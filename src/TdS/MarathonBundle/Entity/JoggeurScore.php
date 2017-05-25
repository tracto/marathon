<?php


namespace TdS\MarathonBundle\Entity;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TdS\MarathonBundle\Entity\JoggeurScoreRepository")
 */
class JoggeurScore
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

 
  /**
   * @ORM\OneToOne(targetEntity="TdS\MarathonBundle\Entity\Joggeur", inversedBy="joggeurScore", cascade={"persist","remove"})
   * @ORM\JoinColumn(nullable=false)
   */
  private $joggeur;

  


  /**
   * @ORM\OneToMany(targetEntity="TdS\MarathonBundle\Entity\Score", mappedBy="joggeurScore", cascade={"persist","remove"})
   * @ORM\JoinColumn(nullable=true)
   */
  private $scores; 


  /**
     * @var integer
     *
     * @ORM\Column(name="pointstogive", type="integer",nullable=false)
     */
  private $pointstogive=0;


  private $total=0;





  public function __construct(){
    $this->scores = new ArrayCollection();
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



  public function setScore(Score $score){
    $this->score[] = $score;
    return $this;
  }

  public function getScore(){
    return $this->score;
  }



  public function addScore(Score $score)
  {
    $this->score[] = $score;
    $score->setJoggeurScore($this);
    return $this;
  }

  public function removeScore(Score $score)
  {
    $this->scores->removeElement($score);
  }

  public function getScores()
  {
    return $this->scores;
  }



  public function setJoggeur(Joggeur $joggeur){
    $this->joggeur = $joggeur;

    return $this;
  }

  public function getJoggeur(){
    return $this->joggeur;
  }


  

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


  public function getTotal(){
    $this->total=0;
    foreach($this->scores as $score){
      $this->total+=$score->getTotalTheme();
    }
    return $this->total;
  }

  public function setTotal($total){
    $this->total = $total;
    return $this;
  }


}