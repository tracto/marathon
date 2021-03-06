<?php

namespace TdS\MarathonBundle\Controller;


use \ZipArchive;

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

use TdS\MarathonBundle\Form\ThemeCompilType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;





class ThemeController extends Controller{

	public function indexAction(){
    	$em=$this->getDoctrine()->getManager();
    	$listeSaisons=$em->getRepository('TdSMarathonBundle:Saison')
    					 ->findAll();   	
        return $this->render('TdSMarathonBundle:Theme:index.html.twig', array(
        						'listeSaisons'=>$listeSaisons));
    }





    public function viewAction(Request $request, Theme $theme, $id, $autoplay="false"){
    	$em = $this->getDoctrine()->getManager();
    	$theme=$em->getRepository('TdSMarathonBundle:Theme')
	    			->findOneThemeById($id);
	    $em->clear();

    	if ($theme->getStatut() != 0 || ($theme->getStatut() == 0 && $this->getUser() == $theme->getJoggeur()->getUser())){
	    	
	    	
	    	// $theme=$em->getRepository('TdSMarathonBundle:Theme')
	    	// 		->findOneThemeById($id); 

	    	$saison=$theme->getSaison();
	    	              
	        $tabIdTheme=array();
	        $listeThemes=$em->getRepository('TdSMarathonBundle:Theme')
	        		  ->findThemeOrderByDateFinOnlyId();
	        $em->clear();

	        foreach ($listeThemes as $themeItem){
         		if($themeItem->getStatut() != 0){
         			$tabIdTheme[]=$themeItem->getId();
        		
        		}elseif($theme->getStatut() == 0 && $themeItem->getId() == $theme->getId()){
         			$tabIdTheme[]=$themeItem->getId();
         		}	    		
	        }

	        // $idThemeCurrent=$id;
	        $idThemePrec=null;
	        $idThemeSuiv=null;


	        $found_index = array_search($id, $tabIdTheme);
	        if ($found_index === 0){
	          $idThemePrec=end($tabIdTheme);
	        }else{
	          $idThemePrec=$tabIdTheme[$found_index-1];
	        }
	        
	        if ($found_index === sizeOf($tabIdTheme)-1){
	          $idThemeSuiv=$tabIdTheme[0];
	        }else{
	          $idThemeSuiv=$tabIdTheme[$found_index+1];
	        }
	        $current=$tabIdTheme[$found_index];
	        


	        $themePrec=$em->getRepository('TdSMarathonBundle:Theme')
	                          ->findOneThemeById($idThemePrec);

	        $themeSuiv=$em->getRepository('TdSMarathonBundle:Theme')
	                          ->findOneThemeById($idThemeSuiv);

	        
	    	

	        $tdsScoring = $this->container->get('tds_marathon.scoring');
	        $listeJoggeursScore=$tdsScoring->getAllJoggeursScoresOfTheme($theme);

	    	
		    return $this->render('TdSMarathonBundle:Theme:view.html.twig', array(
		      'saison' => $saison,
		      'listeJoggeursScore'=>$listeJoggeursScore,
		      'themePrec'=>$themePrec,
		      'themeSuiv'=>$themeSuiv,
		      'theme' => $theme,     
		    ));

	    }else{
		    	$request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    	return $this->redirectToRoute('tds_home');
		}
	}



	public function viewVignetteAction(Theme $theme, $id){
      $em = $this->getDoctrine()->getManager();
      return $this->render('TdSMarathonBundle:Theme:view-vignette.html.twig',array(
                 'theme'=>$theme));
  	}


	


    public function addAction(Request $request,$statut){
    	if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') || ($this->get('security.context')->isGranted('ROLE_USER')) && $statut == 0 ){
		    	$theme=new Theme();
				$form=$this->get('form.factory')->create(new ThemeType(), $theme, array('statut' => $statut)); 
				$form->handleRequest($request);

				if($form->isValid()){

					$em=$this->getDoctrine()->getManager();
					$theme->setStatut($statut);
					if(!$theme->getJoggeur()){
						$user=$this->getUser();
						$theme->setJoggeur($user->getJoggeur());
					}

					if($statut==0){
						$theme->setDateDebut(new \DateTime("now"));
						$theme->setDateFin(new \DateTime("now"));
						$lastSaison=new Saison();
						$lastSaison=$em->getRepository('TdSMarathonBundle:Saison')
		    					 		->findOneBy(array('statut' => 1));		 		
						$theme->setSaison($lastSaison);
					}

					
					$em->persist($theme);
					$em->flush();

					$request->getSession()->getFlashBag()->add('notice','theme bien enregistré.');

					return $this->redirect($this->generateUrl('tds_marathon_theme_view',array('id'=>$theme->getId())));
				}

		        return $this->render('TdSMarathonBundle:Theme:add.html.twig', array(
		        	'form'=>$form->createView(),
		        	'statut'=>$statut
		        	));
		    }else{
		    	$request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    	return $this->redirectToRoute('tds_dashboard');
		    }
    }

    public function editAction(Request $request, Theme $theme, $id){
    	$em=$this->getDoctrine()->getManager();

    	$theme=$em->getRepository('TdSMarathonBundle:Theme')
	    			->findOneThemeById($id);

    	$statut=$theme->getStatut();

    	if($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') || ($this->get('security.context')->isGranted('ROLE_USER') && $this->getUser() == $theme->getJoggeur()->getUser())){
	    			  	
		  	$form=$this->createForm(new ThemeEditType(),$theme, array('statut' => $statut));

		  	if($form->handleRequest($request)->isValid()){

		  		$em->flush();
		  		$request->getSession()->getFlashBag()->add('notice','Thème bien modifié.');
		  
		  		return $this->redirect($this->generateUrl('tds_marathon_theme_view',array('id'=>$theme->getId())));

		  	}else{

		  	return $this->render('TdSMarathonBundle:Theme:edit.html.twig',array(
		  		'form'=>$form->createView(),
		  		'theme'=>$theme,
		  		'statut'=>$statut));
		  	}

		}else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}
    }


    public function deleteAction(Theme $theme, $id, Request $request){
    	if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
			$em=$this->getDoctrine()->getManager();
			$theme = $em->getRepository('TdSMarathonBundle:Theme')
						->findOneBy(array('id' => $id));

			if($theme!=null){
				$em->remove($theme);
	        	$em->flush();
	        	$request->getSession()->getFlashBag()->add('notice','Thème supprimé.');
			}

			
			$listeSaisons=$em->getRepository('TdSMarathonBundle:Saison')
	    					 ->findAll();
	    	
	        return $this->render('TdSMarathonBundle:Theme:index.html.twig', array(
	        						'listeSaisons'=>$listeSaisons));
	    }else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}
	}


    public function switchAction(Request $request){
    	if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
	    	if($request->isXmlHttpRequest()){
		    	$em=$this->getDoctrine()->getManager(); 

		    	$threeThemes = $em->getRepository('TdSMarathonBundle:Theme')
		    					->find3Themes();



		    	return $this->render('TdSMarathonBundle:Theme:switch.html.twig',array(
		    				'threeThemes'=>$threeThemes
			  			));
	    	}
    	}else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}
	  	
    }

    public function switchValidationAction(Request $request){
    	if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
	    	$referer = $this->getRequest()->headers->get('referer');
	    	$em=$this->getDoctrine()->getManager(); 

	    	$tdsScore = $this->container->get('tds_marathon.scoring');
	    	$tdsSaison = $this->container->get('tds_marathon.saison');

	        $saison=$tdsSaison->getCurrSaison();

 
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
		    	$tdsScore->setTakenPointsToJoggeurs($saison,$postTheme);
			}

		    
	        if($currentTheme){
	  			$joggeursDuTheme=$tdsScore->setFastPointsToJoggeurs($currentTheme);
	  			foreach($joggeursDuTheme as $joggeurDuTheme){
	  				if($joggeursDuTheme[0] && $currentTheme){
	  					$joggeurScore=$joggeurDuTheme->getJoggeurScore();
            			$currentTheme->setJoggeurChronique($joggeursDuTheme[0]);
            			$joggeurScore->setPointstogive(15);            
        			}
	  			}
	  		}


		    if($draftModeTheme){
				$draftModeTheme->setStatut(1);
			}

		    if($postTheme) {
		    	$postTheme->setStatut(3);
		    }
						

			if($currentTheme){
				$currentTheme->setStatut(2);
				$em->persist($currentTheme); 
			}

			$em->flush();



			$request->getSession()->getFlashBag()->add('notice','Changement de thème effectué avec succès.');
			return $this->redirect($referer);

		}else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}

    }


    public function activeValidationAction(Request $request){
    	if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
	    	$referer = $this->getRequest()->headers->get('referer');
	    	$em=$this->getDoctrine()->getManager(); 


		    $draftmodeTheme = $em
		      			->getRepository('TdSMarathonBundle:Theme')
		      			->findOneBy(array('statut' => 0));  		    	    			
			if($draftmodeTheme){
				$draftmodeTheme->setStatut(1);
			}
								
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice',"thème d'attente activé avec succès.");
			return $this->redirect($referer);

		}else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}

    }

    public function addChroniqueAction(Request $request, $theme_id, $joggeurChronique_id){
    	$em = $this->getDoctrine()->getManager();
    	$joggeurChronique = $em->getRepository('TdSMarathonBundle:Joggeur')
		      				   ->findJoggeurById($joggeurChronique_id);

		

    	if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')|| ($this->get('security.context')->isGranted('ROLE_USER')) && $this->getUser() == $joggeurChronique->getUser() ){
	    	
	    	$theme = $em
		      			->getRepository('TdSMarathonBundle:Theme')
		      			->findOneBy(array('id' => $theme_id));  

		     
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
    	}else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}
	}



    public function editChroniqueAction(Request $request, $theme_id, $joggeurChronique_id){
    	$em = $this->getDoctrine()->getManager();

    	$joggeurChronique = $em
	      			->getRepository('TdSMarathonBundle:Joggeur')
	      			->findJoggeurById($joggeurChronique_id);

    	if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')|| ($this->get('security.context')->isGranted('ROLE_USER')) && $this->getUser() == $joggeurChronique->getUser() ){

	    	$theme = $em
		      			->getRepository('TdSMarathonBundle:Theme')
		      			->findOneBy(array('id' => $theme_id));  

		     

		    

		    $form=$this->get('form.factory')->create(new ThemeChroniqueEditType(),$theme); 
			$form->handleRequest($request);

			if($form->isValid()){
				
				$theme->setJoggeurChronique($joggeurChronique);			
				$em->flush();

				$request->getSession()->getFlashBag()->add('notice','chronique bien modifiée.');

				return $this->redirect($this->generateUrl('tds_marathon_theme_view',array('id'=>$theme->getId())));
			}

	    	return $this->render('TdSMarathonBundle:Theme:editchronique.html.twig',array(
	    						 "theme"=>$theme,
	    						 'form'=>$form->createView(),
		  				  ));
    	}else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}
   	}




   	public function compilsViewAction(Request $request){
   		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
	   		$em = $this->getDoctrine()->getManager();

	   		$listeThemes=$em->getRepository('TdSMarathonBundle:Theme')
		        		  ->findAllThemes();

		    return $this->render('TdSMarathonBundle:Theme:compilsview.html.twig',
					  		array(
				    			"listeThemes"=>$listeThemes,
					  		));
		  	

	    }else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}

   	}


   	public function compilCreateAction(Request $request,$id){
   		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
	   		$em = $this->getDoctrine()->getManager();

	   		$theme=$em->getRepository('TdSMarathonBundle:Theme')
		        		  ->findOneThemeById($id);

		    

		    $form=$this->createForm(new ThemeCompilType(),$theme);
             
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            	
            	$stringToolbox = $this->container->get('string.toolbox');
            	
            	// formatage du titre
            	$titre=$theme->getTitre();
            	$folderTitle = str_replace(' ', '-', $titre);
            	$folderTitle = str_replace('/', '-', $folderTitle);
            	$folderTitle = str_replace('_', '-', $folderTitle);
            	$folderTitle=$stringToolbox->replaceAllAccents($folderTitle);
   				$folderTitle=$folderTitle."_".$id;
   				$folderDest="uploads/compils/".$folderTitle;

				$zip = new ZipArchive();
				$zipname = $folderTitle.".zip"; 
				$zip->open("uploads/compils/".$zipname,  ZipArchive::CREATE);

				// print_r("<br/>titres :");
				foreach($theme->getMusicTitles() as $musicTitle){
					

					// musictitle source
					$musicTitleSource=$musicTitle->getWebPath();

					// formatage du titre de chaque musictitle
					$musicTitleTitre = str_replace(' ', '', $musicTitle->getTitre());
					$musicTitleAuteur = str_replace(' ', '', $musicTitle->getJoggeur()->getPseudo());
					$musicTitleString=$musicTitleTitre."-".$musicTitleAuteur;
					$musicTitleString=$stringToolbox->replaceAllAccents($musicTitleString).".mp3";

					// musictitle destination
					$musicTitleDest=$folderDest."/".$musicTitleString;

				    // Add current file to archive
					$zip->addFile($musicTitleSource,$musicTitleString);
					
					if($theme->getZip()!==1){
						$theme->setZip($folderTitle);

					}						
				}
				$em->flush();
				
				$zip->close();
				$request->getSession()->getFlashBag()->add('notice',"Zip créé!");
            }

		  	return $this->render('TdSMarathonBundle:Theme:compilcreate.html.twig',
					  		array(
				    			"theme"=>$theme,
				    			'form'=>$form->createView()
					  		));

	    }else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}

   	}


   	public function compilDeleteAction(Request $request,$id){
   		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
	   		$em = $this->getDoctrine()->getManager();

	   		$theme=$em->getRepository('TdSMarathonBundle:Theme')
		        		  ->findOneThemeById($id);

		    if($theme->getId() || $theme->getId() !== null ){
		    	$themeZip=$theme->getZip();
		    	$themeZipPath="uploads/compils/".$themeZip.".zip";
		    	unlink($themeZipPath);
		    	$theme->setZip(null);
		    	$em->flush();
		    	$request->getSession()->getFlashBag()->add('notice',"Zip supprimé : ".$themeZip.".zip");
		    }

		    $listeThemes=$em->getRepository('TdSMarathonBundle:Theme')
		        		  ->findAllThemes();

		    return $this->render('TdSMarathonBundle:Theme:compilsview.html.twig',
					  		array(
				    			"listeThemes"=>$listeThemes,
					  		));

	    }else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}
   	}
   	



}