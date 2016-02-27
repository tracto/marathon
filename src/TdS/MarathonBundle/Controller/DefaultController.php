<?php

namespace TdS\MarathonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

	public function menuAction(){	    
	    return $this->render('TdSMarathonBundle:Default:menu.html.twig');
	}
	
    public function indexAction($name){
        return $this->render('TdSMarathonBundle:Default:index.html.twig', array('name' => $name));
    }
}
