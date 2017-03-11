<?php
namespace TdS\MarathonBundle\Services;

use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\Saison;
use TdS\MarathonBundle\Entity\Theme;
use TdS\MarathonBundle\Entity\JoggeurScore;
use TdS\MarathonBundle\Entity\Score;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

class TdSScoring {

	private $em;

	/**
	 * @InjectParams({
	 *    "em" = @Inject("doctrine.orm.entity_manager")
	 * })
	 */
	public function __construct(EntityManager $em)
	{
	    $this->em = $em;
	}


	public function getAllJoggeursScoresOfSaison($saison){
		$listeJoggeursScore = $this->em
				->getRepository('TdSMarathonBundle:JoggeurScore')
		      	->findAllBySaison($saison);

		$listeJoggeursScore = $this->sortScorebyTotal($listeJoggeursScore);

		return $listeJoggeursScore;
	}


	public function getAllJoggeursScoresOfTheme($theme){
		$listeJoggeursScore = $this->em
		          ->getRepository('TdSMarathonBundle:JoggeurScore')
		          ->findAllByTheme($theme);

		$listeJoggeursScore = $this->sortScorebyTotal($listeJoggeursScore);

		return $listeJoggeursScore;
	}



	public function getAllSaisonScoresOfJoggeur($saison, $joggeur){
		$joggeurScore = $this->em
                        ->getRepository('TdSMarathonBundle:JoggeurScore')
                        ->findJoggeurBySaison($saison, $joggeur);


        if($joggeurScore){
            $joggeurScore=$joggeurScore[0];         
            $joggeur->setJoggeurScore($joggeurScore);
        }

		return $joggeur;
	}


    public function getAllSaisonScoresOfJoggeurScore($saison, $joggeur){
        $joggeurScore = $this->em
                        ->getRepository('TdSMarathonBundle:JoggeurScore')
                        ->findJoggeurBySaison($saison, $joggeur);

        return $joggeurScore;
    }





	public function getTotalList($list){
		$sum=0;
		foreach($list as $item){
			$sum+= $item;
		}
		return $sum;
	}

	public function getScoreTotal($heartPoints, $fastPoints){
		$scoreTotal= $heartPoints + $fastPoints;
		return $scoreTotal;
	}

	public function compareScorebyTotal($a, $b) {
        if($b->getTotal() == $a->getTotal()){ return 0 ; }
        return ($b->getTotal() < $a->getTotal()) ? -1 : 1;
    }

    public function sortScorebyTotal($list){
    	usort($list, array($this,"compareScorebyTotal"));
    	return $list;
    }

    

    // FONCTIONS DU WALL OF FAME

    public function wof_jogEgoiste($listeJoggeursScore){
    	$wof_jogEgoiste=$this->getIdJogFame($listeJoggeursScore,'Takenpoints','arsort');
    	return $wof_jogEgoiste; 
    }

    public function wof_jogDonJuan($listeJoggeursScore){
    	$wof_jogDonJuan=$this->getIdJogFame($listeJoggeursScore,'Heartpoints','arsort');
    	return $wof_jogDonJuan;  
    }

    public function wof_jogPetitGros($listeJoggeursScore){
    	$wof_jogPetitGros=$this->getIdJogFame($listeJoggeursScore,'Fastpoints','asort');
    	return $wof_jogPetitGros; 
    }

    public function wof_jogLfdy($listeJoggeursScore){
    	$wof_jogLfdy=$this->getIdJogFame($listeJoggeursScore,'Fastpoints','arsort');
    	return $wof_jogLfdy; 
    }

    public function wof_jogWinner($listeJoggeursScore){
    	$wof_jogWinner=$this->getIdJogFame($listeJoggeursScore,'TotalTheme','arsort');
    	return $wof_jogWinner; 
    }

    public function wof_jogLoser($listeJoggeursScore){
    	$wof_jogLoser=$this->getIdJogFame($listeJoggeursScore,'TotalTheme','asort');
    	return $wof_jogLoser; 
    }
    

    public function getIdJogFame($listeJoggeursScore,$col,$sort){
    	$filtre='get'.$col;
    	$jogFameArray=array();
    	foreach($listeJoggeursScore as $joggeurScore){
    		$totalFame=0;
    		foreach($joggeurScore->getScores() as $score){
    			$totalFame+=$score->$filtre();   			
    		}
    		$jogFameArray[$joggeurScore->getJoggeur()->getId()]=$totalFame;   		
    	}
    	$sort($jogFameArray);
 		$tabIdJoggeur=array_keys($jogFameArray);
	    $idJoggeur=$tabIdJoggeur[0];

	    if(!$idJoggeur){
	    	$idJoggeur=1;
	    }

		$wof_jogFame = $idJoggeur;
    	return $wof_jogFame;
    }




    // FONCTIONS QUI ATTRIBUENT LES POINTS

    public function setTakenPointsToJoggeurs(Saison $saison,Theme $postTheme){
        $scoresPostTheme=$this->em->getRepository('TdSMarathonBundle:Score')
                            ->findBy(array('theme' => $postTheme));
        if(!empty($scoresPostTheme)){
            foreach($scoresPostTheme as $scorePostTheme){
                $joggeurScore=$scorePostTheme->getJoggeurScore();
                if($postTheme->getSaison()==$saison){
                    $scorePostTheme->setTakenpoints($joggeurScore->getPointstogive());
                }
                $joggeurScore->setPointstogive(0);    
            }
        }
        return $scoresPostTheme; 
    }


    public function setFastPointsToJoggeurs(Theme $currentTheme){
        $musicTitlesDuTheme=$currentTheme->getMusicTitles();
        $joggeursDuTheme= new ArrayCollection();
        foreach($musicTitlesDuTheme as $musicTitleDuTheme){
            if (!$joggeursDuTheme->contains($musicTitleDuTheme->getJoggeur())) {
                $joggeursDuTheme->add($musicTitleDuTheme->getJoggeur());
            }
        }
        $i=$joggeursDuTheme->count();


        foreach($joggeursDuTheme as $joggeurDuTheme){
            $joggeurScore=$joggeurDuTheme->getJoggeurScore();

            if(empty($joggeurDuTheme->getJoggeurScore())){
                $joggeurScore= new JoggeurScore;
                $joggeurDuTheme->setJoggeurScore($joggeurScore); 
                $em->persist($joggeurDuTheme);              
            }

             if(empty($joggeurScore->getJoggeur())){
                $joggeurScore->setJoggeur($joggeurDuTheme);             
             }

            $score= new Score;
            $score->setJoggeurScore($joggeurScore);
            $score->setTheme($currentTheme);
            $score->setFastpoints($i);

            $joggeurScore->addScore($score);

            $this->em->persist($score);
            $this->em->persist($joggeurScore);
            $this->em->persist($joggeurDuTheme); 
            
            $i--;
        }

        return $joggeursDuTheme;
    }
    



}
