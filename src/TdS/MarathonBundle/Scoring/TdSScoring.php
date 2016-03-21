<?php
namespace TdS\MarathonBundle\Scoring;

class TdSScoring {
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
}
