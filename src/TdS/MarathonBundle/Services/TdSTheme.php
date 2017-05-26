<?php
namespace TdS\MarathonBundle\Services;

use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\Theme;
// use TdS\MarathonBundle\Entity\JoggeurScore;
use Doctrine\ORM\EntityManager;

class TdSTheme {

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



	public function getJoggeurForDraftmodeTheme(Theme $theme){
		$musicTitle10th = $this->em
		    ->getRepository('TdSMarathonBundle:MusicTitle')
		    ->getDixieme($theme);
		if(!empty($musicTitle10th)){
			$JoggeurForDraftmodeTheme=$musicTitle10th->getJoggeur();
		}else{
			$JoggeurForDraftmodeTheme=null;
		}
		
		return $JoggeurForDraftmodeTheme;  
	}


}