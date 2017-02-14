<?php
namespace TdS\MarathonBundle\Scoring;

use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\Saison;
// use TdS\MarathonBundle\Entity\JoggeurScore;
use Doctrine\ORM\EntityManager;

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
		$wof_jogFame = $this->em
			          ->getRepository('TdSMarathonBundle:Joggeur')
			          ->findOneBy(array('id'=>$idJoggeur));
		
    	return $wof_jogFame;
    }

}
