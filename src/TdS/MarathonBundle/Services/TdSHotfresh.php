<?php
namespace TdS\MarathonBundle\Services;

use Doctrine\ORM\EntityManager;



class TdSHotfresh {

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


	public function showContent(){
		// $content = $this->em
		//     ->getRepository('TdSMarathonBundle:MusicTitle')
		//     ->getDixieme($theme);
		
		$content = $this->em
		    ->getRepository('TdSMarathonBundle:Hotfresh')
		    ->findOneById(array('id',1));
		
		return $content;  
	}


}