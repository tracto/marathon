<?php
namespace TdS\MarathonBundle\Services;

use TdS\MarathonBundle\Entity\Saison;
use TdS\MarathonBundle\Entity\Theme;
use Doctrine\ORM\EntityManager;


class TdSSaison {

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


	public function allSaison(){
	 	$saisonListe=$this->em
	      	->getRepository('TdSMarathonBundle:Saison')
	      	->findAll();

	      	 return array (
              "saisonListe" => $saisonListe,
      		);
	}



	public function getCurrSaison(){
		$saison=$this->em
	      	->getRepository('TdSMarathonBundle:Saison')
	      	->findSaisonByStatut(1);


	    if(!$saison){
	    	$saison=$this->em
	      	->getRepository('TdSMarathonBundle:Saison')
	    	->findLastOne();
	    }
	    $this->em->clear();
	    return $saison;	    	
	}


	public function getSaisonOfTheme(Theme $theme){
	    $saison=$theme->getSaison();
	    return $saison;
	}



	public function getSaison(){
		$saison=$this->em
	      	->getRepository('TdSMarathonBundle:Saison')
	      	->findOneBy(array('statut' => 1));
	    if($saison){
	    	$saisonTitre=$saison->getTitre();
	    }else{
	    	$saisonTitre="";
	    }
		return $saisonTitre;
	}
}