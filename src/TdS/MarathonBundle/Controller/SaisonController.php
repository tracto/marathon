<?php

namespace TdS\MarathonBundle\Controller;

use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\JoggeurScore;
use TdS\MarathonBundle\Entity\Score;
use TdS\MarathonBundle\Entity\Saison;
use TdS\MarathonBundle\Entity\MusicTitle;
use TdS\MarathonBundle\Entity\Theme;
use TdS\UserBundle\Entity\User;
use TdS\MarathonBundle\Form\SaisonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;




class SaisonController extends Controller{

public function disactiveValidationAction(Request $request){
    	$referer = $this->getRequest()->headers->get('referer');
    	$em=$this->getDoctrine()->getManager(); 



	    $saisonActive = $em
	      			->getRepository('TdSMarathonBundle:Saison')
	      			->findOneBy(array('activate' => 1));  	

    	    
		$saisonActive->setActivate(0);

		$themeActive = $em
	      		->getRepository('TdSMarathonBundle:Theme')
	      		->findOneBy(array('activate' => 1));
	    


	   	$themePost = $em
	      		->getRepository('TdSMarathonBundle:Theme')
	      		->findOneBy(array('postActivate' => 1));

	    $scoresPostTheme=$em->getRepository('TdSMarathonBundle:Score')
	    					->findBy(array('theme' => $themePost));
	    

	    $listeJoggeursScore = $em
				->getRepository('TdSMarathonBundle:JoggeurScore')
				->findAll();

		
			if(empty($scoresPostTheme)){
				$musicTitlesDuTheme=$themePost->getMusicTitles();
		    	$joggeursPostTheme= new ArrayCollection();
		    	if(!empty($musicTitlesDuTheme)){
		        	foreach($musicTitlesDuTheme as $musicTitleDuTheme){
			            if (!$joggeursDuTheme->contains($musicTitleDuTheme->getJoggeur())) {
			                $joggeursPostTheme->add($musicTitleDuTheme->getJoggeur());
			            }
		        	}
	        	}
				if(!empty($joggeursPostTheme)){
		        	foreach($joggeursPostTheme as $joggeurPostTheme){
						$scorePostTheme= new Score;
						$scorePostTheme->setJoggeurScore($joggeurPostTheme);
						$scorePostTheme->setTheme($themePost);
						$scorePostTheme->setTakenpoints($scorePostTheme->getJoggeurScore()->getPointstogive());
						$em->persist($scorePostTheme);
					}
				}
			}else{
				foreach($scoresPostTheme as $scorePostTheme){
					$scorePostTheme->setTakenpoints($scorePostTheme->getJoggeurScore()->getPointstogive());
					$em->persist($scorePostTheme);
				}
			}


		foreach($listeJoggeursScore as $joggeurScore){			
        	$joggeurScore->setPointstogive(0);
        	$em->persist($joggeurScore);
        	
		}
		if(!empty($themeActive)){
			$themeActive->setActivate(0);
			$em->persist($themeActive);
		}
		
		$themePost->setPostActivate(0);
		
		$em->persist($themePost);
		$em->persist($saisonActive);
		

				
		$em->flush();
		$this->redirect($referer);

    }


    public function addSaisonAction(Request $request){
 
		$saison=new Saison();
		$form=$this->get('form.factory')->create(new SaisonType(), $saison); 
		$form->handleRequest($request);

		if($form->isValid()){
			$em=$this->getDoctrine()->getManager();
			$saison->setActivate(1);
			$em->persist($saison);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice','theme bien enregistré.');
			
			return $this->redirect($this->generateUrl('tds_marathon_theme_home'));
		}

        return $this->render('TdSMarathonBundle:Saison:add.html.twig', array('form'=>$form->createView()));
	}
}


