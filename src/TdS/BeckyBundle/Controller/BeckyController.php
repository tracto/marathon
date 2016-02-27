<?php

namespace TdS\BeckyBundle\Controller;

use TdS\BeckyBundle\Entity\Becky;
use TdS\BeckyBundle\Form\BeckyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\Common\Collections\ArrayCollection;


class BeckyController extends Controller {
 	public function infosAction(Request $request){
	    $em=$this->getDoctrine()->getManager(); 
	    $beckyInfos  = $em
				->getRepository('TdSBeckyBundle:Becky')
				->getRandom();
  		return $this->render('TdSBeckyBundle:Becky:beckyInfos.html.twig',array(
	    		"beckyInfos"=>$beckyInfos
	    ));
	  	
    }

    public function listeAction(Request $request){

    	$em=$this->getDoctrine()->getManager();
    	$beckyListe=$em ->getRepository('TdSBeckyBundle:Becky')
    					->findAll();

    	$becky=new Becky();
    	$formBecky=$this->createForm(new BeckyType(),$becky);
		  	
		if($formBecky->handleRequest($request)->isValid()){
			$em=$this->getDoctrine()->getManager();
			$em->persist($becky);
			$em->flush();
		  	$request->getSession()->getFlashBag()->add('notice','phrase de becky ajoutée.');
		  	return $this->redirect($this->generateUrl('tds_becky_liste_phrases',array("beckyListe"=>$beckyListe,
	    		'form'=>$formBecky->createView())));
		}

    	return $this->render('TdSBeckyBundle:Becky:liste.html.twig',array(
	    		"beckyListe"=>$beckyListe,
	    		'form'=>$formBecky->createView()
	    		));

    }


    public function deleteAction(Becky $becky, $id, Request $request){
		$em=$this->getDoctrine()->getManager();

		$beckyListe=$em ->getRepository('TdSBeckyBundle:Becky')
    					->findAll();

    	$beckyNew=new Becky();
		$formBecky=$this->createForm(new BeckyType(),$beckyNew);

		if($becky!=null){
			$em->remove($becky);
        	$em->flush();

        	return $this->redirect($this->generateUrl('tds_becky_liste_phrases',array("beckyListe"=>$beckyListe,
	    		'form'=>$formBecky->createView())));
		}
		

		return $this->render('TdSBeckyBundle:Becky:liste.html.twig',array(
	    		"beckyListe"=>$beckyListe,
	    		'form'=>$formBecky->createView()
	    ));
	}



}