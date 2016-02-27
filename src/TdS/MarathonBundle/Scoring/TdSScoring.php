<?php
namespace TdS\MarathonBundle\Scoring;

class TdSScoring{
	public function getScoreTotal($score, $heartPoints, $lastPoints){
		$scoreTotal=$score + $heartPoints + $lastPoints;
		return $scoreTotal;
	}
}