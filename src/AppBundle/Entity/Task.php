<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Task{
	public $remainingPoints;

	protected $tags;

	public function __construct(){
		$this->tags=new ArrayCollection();
	}

	public function getRemainingPoints(){
		return $this->remainingPoints;
	}

	public function setRemainingPoints($remainingPoints){
		$this->remainingPoints=$remainingPoints;
	}

	public function getTags(){
		return $this->tags;
	}
}