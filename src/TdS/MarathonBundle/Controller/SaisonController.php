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
use TdS\MarathonBundle\Form\SaisonEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// use Symfony\Component\HttpFoundation\Session\Session;




class SaisonController extends Controller{
	public function ViewAction(Request $request, Saison $saison, $id){
		$em=$this->getDoctrine()->getManager();

        $saison=$em->getRepository('TdSMarathonBundle:Saison')
                   ->findSaisonWithThemes($id);

        $tdsScoring = $this->container->get('tds_marathon.scoring');
        $joggeursScoresOfSaison=$tdsScoring->getAllJoggeursScoresOfSaison($saison);


        $musicTitles=$em->getRepository('TdSMarathonBundle:MusicTitle')
	      			->findAllBySaison($saison);
	    shuffle($musicTitles);


        return $this->render('TdSMarathonBundle:saison:view.html.twig', array(
        					'saison'=>$saison,
        					'musicTitles'=>$musicTitles,
        					'joggeursScoresOfSaison'=>$joggeursScoresOfSaison       						
        ));		
	}


	public function disactiveValidationAction(Request $request){
		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
	    	$referer = $this->getRequest()->headers->get('referer');
	    	$em=$this->getDoctrine()->getManager(); 

	    	$tdsScore = $this->container->get('tds_marathon.scoring');

		    $saisonActive = $em
		      			->getRepository('TdSMarathonBundle:Saison')
		      			->findOneBy(array('statut' => 1));  	

	    	if($saisonActive){    
				$saisonActive->setStatut(2);
			}


		    
		    $threeThemes = $em->getRepository('TdSMarathonBundle:Theme')
		    					->find3Themes();


		    $draftModeTheme=null;
		    $currentTheme=null;
		    $postTheme=null;
		    
		    foreach($threeThemes as $themeItem){
		    	if($themeItem->getStatut()==0){
		    		$draftModeTheme=$themeItem;
		    	}elseif($themeItem->getStatut()==1){
		    		$currentTheme=$themeItem;
		    	}elseif($themeItem->getStatut()==2){
		    		$postTheme=$themeItem;
		    	}
		    }

		    if($postTheme){
		    	$tdsScore->setTakenPointsToJoggeurs($saisonActive,$postTheme);
		    	$postTheme->setStatut(3);
			}


			if($currentTheme){
				$joggeursDuTheme=$tdsScore->setFastPointsToJoggeurs($currentTheme);
				foreach($joggeursDuTheme as $joggeurDuTheme){
	  				if($joggeursDuTheme[0] && $currentTheme){
	  					$joggeurScore=$joggeurDuTheme->getJoggeurScore();
            			$currentTheme->setJoggeurChronique($joggeursDuTheme[0]);           
        			}
	  			}
	  			$currentTheme->setStatut(3);
	  			$em->persist($currentTheme);
			}


			if($postTheme) {
		    	$postTheme->setStatut(3);
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


    public function addAction(Request $request){
 		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
			$saison=new Saison();
			$form=$this->get('form.factory')->create(new SaisonType(), $saison); 
			$form->handleRequest($request);

			if($form->isValid()){
				$em=$this->getDoctrine()->getManager();
				$saison->setType(1);
				$saison->setStatut(1);
				$em->persist($saison);
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice','Nouvelle saison activée.');
				
				return $this->redirect($this->generateUrl('tds_dashboard'));
			}

	        return $this->render('TdSMarathonBundle:Saison:add.html.twig', array('form'=>$form->createView()));

        }else{
		    	$request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    	return $this->redirectToRoute('tds_dashboard');
		}
	}


	public function editAction(Saison $saison, $id, Request $request){
 		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
			$em=$this->getDoctrine()->getManager();		  	
		  	$form=$this->createForm(new SaisonEditType(),$saison);

		  	if($form->handleRequest($request)->isValid()){

		  		$em->flush();
		  		$request->getSession()->getFlashBag()->add('notice','Saison bien modifiée.');
		  
		  		return $this->redirect($this->generateUrl('tds_marathon_saison_view',array('id'=>$saison->getId())));

		  	}else{

		  	return $this->render('TdSMarathonBundle:Saison:edit.html.twig',array(
		  		'form'=>$form->createView(),
		  		'saison'=>$saison));
		  	}

        }else{
		    	$request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    	return $this->redirectToRoute('tds_dashboard');
		}
	}
}


