<?php
namespace TdS\MarathonBundle\Scoring;

use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\JoggeurScore;
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

	    $idJoggeur=array_keys($jogFameArray)[0];
	    $wof_jogFame = $this->em
		          ->getRepository('TdSMarathonBundle:Joggeur')
		          ->findOneBy(array('id'=>$idJoggeur));

    	return $wof_jogFame;
    }

}
