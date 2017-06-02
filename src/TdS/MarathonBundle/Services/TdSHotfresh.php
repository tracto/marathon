<?php
namespace TdS\MarathonBundle\Services;

use TdS\MarathonBundle\Entity\Hotfresh;
use Doctrine\ORM\EntityManager;
use TdS\MarathonBundle\Form\HotfreshType;



class TdSHotfresh {

	private $em;

     
    public function __construct(EntityManager $em)
    {
    	$this->em = $em;
    }




	public function showContent(){		
		$content = $this->em
		    ->getRepository('TdSMarathonBundle:Hotfresh')
		    ->findOneById(array('id',1));
		
		return $content;  
	}


	


}