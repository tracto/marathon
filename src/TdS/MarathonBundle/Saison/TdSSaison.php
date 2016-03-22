<?php
namespace TdS\MarathonBundle\Saison;

use TdS\MarathonBundle\Entity\Saison;
use Doctrine\ORM\EntityManager;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;


class TdSSaison {

	public function __construct(EntityManager $entityManager){
    	$this->em = $entityManager;
	}


	public function getSaison(){
		$saison=$this->em
	      	->getRepository('TdSMarathonBundle:Saison')
	      	->findOneBy(array('activate' => 1));

		return $saison->getTitre();
	}
}