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
use Symfony\Component\HttpFoundation\Session\Session;




class SaisonController extends Controller{

	public function disactiveValidationAction(Request $request){
		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
	    	$referer = $this->getRequest()->headers->get('referer');
	    	$em=$this->getDoctrine()->getManager(); 



		    $saisonActive = $em
		      			->getRepository('TdSMarathonBundle:Saison')
		      			->findOneBy(array('activate' => 1));  	

	    	if($saisonActive){    
				$saisonActive->setActivate(0);
			}

			$themeActive = $em
		      		->getRepository('TdSMarathonBundle:Theme')
		      		->findOneBy(array('activate' => 1));
		    


		   	$themePost = $em
		      		->getRepository('TdSMarathonBundle:Theme')
		      		->findOneBy(array('postActivate' => 1));

		    if($themePost){
		    	$scoresPostTheme=$em->getRepository('TdSMarathonBundle:Score')
		    					->findBy(array('theme' => $themePost));
		    }
		    
		    

		    $listeJoggeursScore = $em
					->getRepository('TdSMarathonBundle:JoggeurScore')
					->findAll();

			
			if(empty($scoresPostTheme)){
				$joggeursPostTheme= new ArrayCollection();

				if($themePost && $themePost->getMusicTitles()){
					$musicTitlesDuTheme=$themePost->getMusicTitles();
				}
		    	
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
			if($themeActive){
				$themeActive->setActivate(0);
				$em->persist($themeActive);
			}
			
			if($themePost){
				$themePost->setPostActivate(0);
				$em->persist($themePost);
			}
			
			
			$em->persist($saisonActive);
			

					
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice',"Saison fermée");
			return $this->redirect($referer);

		}else{
		    	$request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    	return $this->redirectToRoute('tds_dashboard');
		}

    }


    public function addSaisonAction(Request $request){
 		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
			$saison=new Saison();
			$form=$this->get('form.factory')->create(new SaisonType(), $saison); 
			$form->handleRequest($request);

			if($form->isValid()){
				$em=$this->getDoctrine()->getManager();
				$saison->setActivate(1);
				$em->persist($saison);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice','Nouvelle saison activée.');
				
				return $this->redirect($this->generateUrl('tds_marathon_theme_home'));
			}

	        return $this->render('TdSMarathonBundle:Saison:add.html.twig', array('form'=>$form->createView()));

        }else{
		    	$request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    	return $this->redirectToRoute('tds_dashboard');
		}
	}
}


