<?php

namespace TdS\MarathonBundle\Controller;

use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\MusicTitle;
use TdS\MarathonBundle\Entity\Saison;
use TdS\MarathonBundle\Entity\Theme;
use TdS\MarathonBundle\Entity\JoggeurScore;
use TdS\MarathonBundle\Entity\Score;
use TdS\UserBundle\Entity\User;
use TdS\MarathonBundle\Form\SaisonType;
use TdS\MarathonBundle\Form\ThemeType;
use TdS\MarathonBundle\Form\ThemeEditType;
use TdS\MarathonBundle\Form\ThemeChroniqueType;
use TdS\MarathonBundle\Form\ThemeChroniqueEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;




class ThemeController extends Controller{

	public function indexAction(){
    	$em=$this->getDoctrine()->getManager();
    	$listeSaisons=$em->getRepository('TdSMarathonBundle:Saison')
    					 ->findAll(array(),array('id'=>'desc'));   	
    	
        return $this->render('TdSMarathonBundle:Theme:index.html.twig', array(
        						'listeSaisons'=>$listeSaisons));
    }


    public function viewAction(Request $request, Theme $theme, $id, $autoplay="false"){
    	$em = $this->getDoctrine()->getManager();

    	$listeSaisons=$em->getRepository('TdSMarathonBundle:Saison')
    					 ->findAll(); 


        $tabIdTheme=array();
        foreach ($listeSaisons as $saison){
        	foreach($saison->getThemes() as $itemTheme){
    			$tabIdTheme[]=$itemTheme->getId();
    		}
        }
    	
	    return $this->render('TdSMarathonBundle:Theme:view.html.twig', array(
	      'tabIdTheme'=>$tabIdTheme,
	      'theme' => $theme,     
	    ));
	}


	


    public function addAction(Request $request,$draftmode){
    	$theme=new Theme();
		$form=$this->get('form.factory')->create(new ThemeType(), $theme, array('draftmode' => $draftmode)); 
		$form->handleRequest($request);

		if($form->isValid()){

			$em=$this->getDoctrine()->getManager();
			$theme->setDraftmode($draftmode);
			if(!$theme->getJoggeur()){
				$user=$this->getUser();
				$theme->setJoggeur($user->getJoggeur());
			}

			if($draftmode==1){
				$theme->setDateDebut(new \DateTime("now"));
				$theme->setDateFin(new \DateTime("now"));
				$lastSaison=new Saison();
				$lastSaison=$em->getRepository('TdSMarathonBundle:Saison')
    					 		->findOneBy(array('activate' => 1));		 		
				$theme->setSaison($lastSaison);
			}

			
			$em->persist($theme);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice','theme bien enregistré.');

			return $this->redirect($this->generateUrl('tds_marathon_theme_view',array('id'=>$theme->getId())));
		}

        return $this->render('TdSMarathonBundle:Theme:add.html.twig', array(
        	'form'=>$form->createView(),
        	'draftmode'=>$draftmode
        	));
    }

    public function editAction(Theme $theme, $id, Request $request){
    	
    	$em=$this->getDoctrine()->getManager();

	  	
	  	$form=$this->createForm(new ThemeEditType(),$theme);

	  	if($form->handleRequest($request)->isValid()){

	  		$em->flush();
	  		$request->getSession()->getFlashBag()->add('notice','Thème bien modifié.');
	  
	  		return $this->redirect($this->generateUrl('tds_marathon_theme_view',array('id'=>$theme->getId())));

	  	}else{

	  	return $this->render('TdSMarathonBundle:Theme:edit.html.twig',array(
	  		'form'=>$form->createView(),
	  		'theme'=>$theme));
	  	}
    }


    public function deleteAction(Theme $theme, $id, Request $request){
		$em=$this->getDoctrine()->getManager();
		$theme = $em->getRepository('TdSMarathonBundle:Theme')
					->findOneBy(array('id' => $id));

		if($theme!=null){
			$em->remove($theme);
        	$em->flush();
		}

		
		$listeSaisons=$em->getRepository('TdSMarathonBundle:Saison')
    					 ->findAll();
    	
        return $this->render('TdSMarathonBundle:Theme:index.html.twig', array(
        						'listeSaisons'=>$listeSaisons));
	}


    public function switchAction(Request $request){
    	if($request->isXmlHttpRequest()){
	    	$em=$this->getDoctrine()->getManager(); 

	    	$draftmodeTheme = $em
		      			->getRepository('TdSMarathonBundle:Theme')
		      			->findOneBy(array('draftmode' => 1));  	

	    	$currentTheme = $em
		      			->getRepository('TdSMarathonBundle:Theme')
		      			->findOneBy(array('activate' => 1));

		    $postTheme = $em
		      			->getRepository('TdSMarathonBundle:Theme')
		      			->findOneBy(array('postActivate' => 1));


	    	return $this->render('TdSMarathonBundle:Theme:switch.html.twig',array(
	    						'currentTheme'=>$currentTheme,
	    						'postTheme'=>$postTheme,
	    						'draftmodeTheme'=>$draftmodeTheme
		  				  ));
    	}
	  	
    }

    public function switchValidationAction(Request $request){
    	$referer = $this->getRequest()->headers->get('referer');
    	$em=$this->getDoctrine()->getManager(); 

    	$saison=$em->getRepository('TdSMarathonBundle:Saison')
	      			->findOneBy(array('activate' => 1));

    	$allThemes = $em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findAll();

	    $draftmodeTheme = $em->getRepository('TdSMarathonBundle:Theme')
	      					 ->findOneBy(array('draftmode' => 1));  	

    	$currentTheme = $em->getRepository('TdSMarathonBundle:Theme')
	      				   ->findOneBy(array('activate' => 1));

	    $postTheme = $em->getRepository('TdSMarathonBundle:Theme')
	      				->findOneBy(array('postActivate' => 1));

	    $scoresPostTheme=$em->getRepository('TdSMarathonBundle:Score')
	    					->findBy(array('theme' => $postTheme)); 

	    $allJoggeurs = $em
	      			->getRepository('TdSMarathonBundle:Joggeur')
	      			->findAll();


	    $listeJoggeursScore = $em
          ->getRepository('TdSMarathonBundle:JoggeurScore')
          ->findAllBySaison($saison);

        foreach($allJoggeurs as $joggeur){
        	$joggeurScore=$joggeur->getJoggeurScore();

    	}

    	if(!empty($scoresPostTheme)){
	    	foreach($scoresPostTheme as $scorePostTheme){
	         	$joggeurScore=$scorePostTheme->getJoggeurScore();
	         	$scorePostTheme->setTakenpoints($joggeurScore->getPointstogive());
	        	
	        }
    	}

         foreach($allJoggeurs as $joggeur){
        	$joggeurScore=$joggeur->getJoggeurScore();
        	$joggeurScore->setPointstogive(0);
    	}

	    $musicTitlesDuTheme=$currentTheme->getMusicTitles();
	    $joggeursDuTheme= new ArrayCollection();
        foreach($musicTitlesDuTheme as $musicTitleDuTheme){
            if (!$joggeursDuTheme->contains($musicTitleDuTheme->getJoggeur())) {
                $joggeursDuTheme->add($musicTitleDuTheme->getJoggeur());
            }
        }
        $i=$joggeursDuTheme->count();

        foreach($joggeursDuTheme as $joggeurDuTheme){
        	$joggeurScore=$joggeurDuTheme->getJoggeurScore();

        	if(empty($joggeurDuTheme->getJoggeurScore())){
         		$joggeurScore= new JoggeurScore;
         		$joggeurDuTheme->setJoggeurScore($joggeurScore); 
         		$em->persist($joggeurDuTheme);       		
         	}

         	 if(empty($joggeurScore->getJoggeur())){
         	 	$joggeurScore->setJoggeur($joggeurDuTheme);        		
         	 }

        	
        	$joggeurScore->setPointstogive(10);

        	$score= new Score;
        	$score->setJoggeurScore($joggeurScore);
        	$score->setTheme($currentTheme);
        	$score->setFastpoints($i);

        	$joggeurScore->addScore($score);

        	$em->persist($score);
        	$em->persist($joggeurScore);
        	$em->persist($joggeurDuTheme); 
        	
         	$i--;
        }




	    foreach ($allThemes as $theme) {
	    	$theme->setDraftmode(0);
	    	$theme->setActivate(0);
	    	$theme->setPostActivate(0);
	    }
		
		if($draftmodeTheme){
			$draftmodeTheme->setActivate(1);
		}
		

		if($currentTheme){
			$currentTheme->setPostActivate(1);
			$em->persist($currentTheme); 
		}

		$em->flush();
		return $this->redirect($referer);

    }


    public function activeValidationAction(Request $request){
    	$referer = $this->getRequest()->headers->get('referer');
    	$em=$this->getDoctrine()->getManager(); 


	    $draftmodeTheme = $em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findOneBy(array('draftmode' => 1));  	

    	    
		
		if($draftmodeTheme){
			$draftmodeTheme->setActivate(1);
			$draftmodeTheme->setDraftmode(0);
		}
		

				
		$em->flush();
		return $this->redirect($referer);

    }

    public function addChroniqueAction(Request $request, $theme_id, $joggeurChronique_id){
    	$em = $this->getDoctrine()->getManager();

    	$theme = $em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findOneBy(array('id' => $theme_id));  

	    $joggeurChronique = $em
	      			->getRepository('TdSMarathonBundle:Joggeur')
	      			->findOneBy(array('id' => $joggeurChronique_id)); 

	    

	    $form=$this->get('form.factory')->create(new ThemeChroniqueType(),$theme); 
		$form->handleRequest($request);

		if($form->isValid()){
			$theme->setJoggeurChronique($joggeurChronique);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice','chronique bien enregistrée.');

			return $this->redirect($this->generateUrl('tds_marathon_theme_view',array('id'=>$theme->getId())));
		}

    	return $this->render('TdSMarathonBundle:Theme:addchronique.html.twig',array(
    						 "theme"=>$theme,
    						 'form'=>$form->createView(),
	  				  ));
    }



    public function editChroniqueAction(Request $request, $theme_id, $joggeurChronique_id){
    	$em = $this->getDoctrine()->getManager();

    	$theme = $em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findOneBy(array('id' => $theme_id));  

	    $joggeurChronique = $em
	      			->getRepository('TdSMarathonBundle:Joggeur')
	      			->findOneBy(array('id' => $joggeurChronique_id)); 

	    

	    $form=$this->get('form.factory')->create(new ThemeChroniqueEditType(),$theme); 
		$form->handleRequest($request);

		if($form->isValid()){
			
			$theme->setJoggeurChronique($joggeurChronique);			
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice','chronique bien enregistrée.');

			return $this->redirect($this->generateUrl('tds_marathon_theme_view',array('id'=>$theme->getId())));
		}

    	return $this->render('TdSMarathonBundle:Theme:editchronique.html.twig',array(
    						 "theme"=>$theme,
    						 'form'=>$form->createView(),
	  				  ));
    }


}