<?php

namespace TdS\MarathonBundle\Controller;


use AppBundle\Entity\Task;
use AppBundle\Entity\Tag;
use AppBundle\Form\Type\TaskType;
use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\JoggeurScore;
use TdS\MarathonBundle\Entity\Score;
use TdS\MarathonBundle\Entity\Theme;
use TdS\MarathonBundle\Entity\Saison;
use TdS\UserBundle\Entity\User;
use TdS\MarathonBundle\Entity\MusicTitle;
use TdS\MarathonBundle\Form\JoggeurType;
use TdS\MarathonBundle\Form\JoggeurEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;





class JoggeurController extends Controller{

    public function indexAction(){
    	$em=$this->getDoctrine()->getManager();
    	$listeJoggeurs=$em->getRepository('TdSMarathonBundle:Joggeur')
    					   ->findAllSortByLastLogin();
 

      return $this->render('TdSMarathonBundle:Joggeur:index.html.twig', array(
        						'listeJoggeurs'=>$listeJoggeurs));
    }




    public function viewAction(Joggeur $joggeur, $id){
    	  $em = $this->getDoctrine()->getManager();


        $tdsSaison = $this->container->get('tds_marathon.saison');
        $saison=$tdsSaison->getCurrSaison();

        


        $listeJoggeurs=$em->getRepository('TdSMarathonBundle:Joggeur')
                         ->findAllSortByLastLogin();
        $em->clear();

        $tabIdJoggeur=array();
        foreach($listeJoggeurs as $itemJoggeur){
              $tabIdJoggeur[]=$itemJoggeur->getId();
        }



        $idJoggeurPrec=null;
        $idJoggeurSuiv=null;


        $found_index = array_search($id, $tabIdJoggeur);
        if ($found_index === 0){
          $idJoggeurPrec=end($tabIdJoggeur);
        }else{
          $idJoggeurPrec=$tabIdJoggeur[$found_index-1];
        }
        
        if ($found_index === sizeOf($tabIdJoggeur)-1){
          $idJoggeurSuiv=$tabIdJoggeur[0];
        }else{
          $idJoggeurSuiv=$tabIdJoggeur[$found_index+1];
        }
        $current=$tabIdJoggeur[$found_index];
        


        $joggeurPrec=$em->getRepository('TdSMarathonBundle:Joggeur')
                          ->findJoggeurById($idJoggeurPrec);

        $joggeurSuiv=$em->getRepository('TdSMarathonBundle:Joggeur')
                          ->findJoggeurById($idJoggeurSuiv);


        $tdsScoring = $this->container->get('tds_marathon.scoring');
        $joggeurScoreSaison=$tdsScoring->getAllSaisonScoresOfJoggeurScore($saison, $joggeur);

        $joggeur=$em->getRepository('TdSMarathonBundle:Joggeur')
                    ->findJoggeurById($id);

  	    return $this->render('TdSMarathonBundle:Joggeur:view.html.twig', array(
            'saison'=>$saison,
            'joggeurPrec'=>$joggeurPrec,
            'joggeurSuiv'=>$joggeurSuiv,
  	        'joggeur' => $joggeur,
            'joggeurScoreSaison'=>$joggeurScoreSaison,
  	    ));
	}



  public function morescoresAction(Joggeur $joggeur, $id){
    $em = $this->getDoctrine()->getManager();

    $tdsSaison = $this->container->get('tds_marathon.saison');
    $saison=$tdsSaison->getCurrSaison();
    $currSaisId=$saison->getId();


    $idjoggeurscore= $joggeur->getJoggeurScore()->getId();

    $joggeurScore=$em->getRepository('TdSMarathonBundle:JoggeurScore')
                      ->findJoggeurScoreForAllSaisons($idjoggeurscore, $currSaisId);


     return $this->render('TdSMarathonBundle:Joggeur:morescores.html.twig',array(
                 'joggeurScore'=>$joggeurScore));

  } 






	public function classementAction($saisonid, Request $request){
		$em=$this->getDoctrine()->getManager();
                     
      
        $listeJoggeurs=$em->getRepository('TdSMarathonBundle:Joggeur')
        				->findAll();

        $listeSaisons=$em->getRepository('TdSMarathonBundle:Saison')
                       ->findAll();


        $saison=$em->getRepository('TdSMarathonBundle:Saison')
                      ->findOneBy(array('id' => $saisonid));



        $tdsScoring = $this->container->get('tds_marathon.scoring');
        $listeJoggeursScore=$tdsScoring->getAllJoggeursScoresOfSaison($saison);

     

    	return $this->render('TdSMarathonBundle:Joggeur:classement.html.twig', array(
            'saison'=>$saison,
    				'listeJoggeurs'=>$listeJoggeurs,
            'listeSaisons'=>$listeSaisons,
            'listeJoggeursScore'=>$listeJoggeursScore,
        ));
	}





    public function addAction(Request $request){
      if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
        	$joggeur=new Joggeur();

      		$form=$this->get('form.factory')->create(new JoggeurType(), $joggeur); 
      		$form->handleRequest($request);

      		if($form->isValid()){
                  $targetPath = $request->request->get('_target_path');

      			$em=$this->getDoctrine()->getManager();
      			$em->persist($joggeur);
      			$em->flush();

      			$request->getSession()->getFlashBag()->add('notice','joggeur bien enregistré.');
                  if($targetPath != null){
                      return $this->redirect($targetPath);
                  }else{
                      return $this->redirect($this->generateUrl('tds_marathon_joggeur_view',array('id'=>$joggeur->getId()))); 
                  }			
      		}

              return $this->render('TdSMarathonBundle:Joggeur:add.html.twig', array('form'=>$form->createView()));

          }else{
            $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
            return $this->redirectToRoute('tds_dashboard');
          }
    }

    

    public function editAction(Joggeur $joggeur, $id, Request $request){
      if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') || ($this->get('security.context')->isGranted('ROLE_USER')) && $this->getUser() == $joggeur->getUser() ){
      	$em=$this->getDoctrine()->getManager();	
      	$form = $this->createForm(new JoggeurEditType(), $joggeur);

      	$form->handleRequest($request);
  	  	if ($form->isSubmitted() && $form->isValid()) {	
                $em->persist($joggeur); 		
  	  		  $em->flush();
            $request->getSession()->getFlashBag()->add('notice',"Joggeur modifié avec succès");
  	  		  return $this->redirect($this->generateUrl('tds_marathon_joggeur_view',array('id'=>$joggeur->getId())));
  	  	}else{
  	  	
  	  	return $this->render('TdSMarathonBundle:Joggeur:edit.html.twig',array(
  	  		'form'=>$form->createView(),
  	  		'joggeur'=>$joggeur,
  	  		));
  	  	}
      }else{
          $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
          return $this->redirectToRoute('tds_dashboard');
      }
	  	
    }


    public function deleteAction(Joggeur $joggeur, $id, Request $request){
      if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
        $em=$this->getDoctrine()->getManager();
        $joggeur = $em->getRepository('TdSMarathonBundle:Joggeur')
                    ->findOneBy(array('id' => $id));

        if($joggeur!=null){
            $userAttached=$joggeur->getUser();
            if($userAttached != null){
               
               $userAttached->setJoggeur(null); 
               $em->persist($userAttached);
            }
             $em->remove($joggeur);
             $em->flush();
             $request->getSession()->getFlashBag()->add('notice',"Joggeur supprimé");
        }

        
        $listeJoggeurs=$em->getRepository('TdSMarathonBundle:Joggeur')
                           ->findAll();

        return $this->render('TdSMarathonBundle:Joggeur:index.html.twig', array(
                                'listeJoggeurs'=>$listeJoggeurs));
      }else{
        $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
        return $this->redirectToRoute('tds_dashboard');
      }
    }


    public function addpointsAction(Joggeur $joggeur, $id, Request $request){
      if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') || ($this->get('security.context')->isGranted('ROLE_USER')) && $this->getUser() == $joggeur->getUser() ){
      	   $em=$this->getDoctrine()->getManager();

          $joggeur=$em->getRepository('TdSMarathonBundle:Joggeur')
                             // ->findOneBy(array('id' => $id));
                             ->findJoggeurById($id);

          $tdsSaison = $this->container->get('tds_marathon.saison');
          $saison=$tdsSaison->getCurrSaison();

      	 
          $themePost=$em->getRepository('TdSMarathonBundle:Theme')
                        ->findOneThemeByStatut(2);

          $joggeursDuTheme= new ArrayCollection();

          if($themePost){
            $musicTitlesDuTheme=$themePost->getMusicTitles();
            

            $scoreJoggeurParTheme=$em->getRepository('TdSMarathonBundle:JoggeurScore')
                             ->findJoggeurParTheme($joggeur,$themePost);
            if(!empty($scoreJoggeurParTheme)){
                $scoreJoggeurParTheme=$scoreJoggeurParTheme[0];
            }
            

            foreach($musicTitlesDuTheme as $musicTitleDuTheme){
                if (!$joggeursDuTheme->contains($musicTitleDuTheme->getJoggeur())) {
                    $joggeursDuTheme->add($musicTitleDuTheme->getJoggeur());
                }
            }

            $task=new Task();
          
            foreach($joggeursDuTheme as $joggeurDuTheme){            
                    $tag=new Tag();
                    $tag->idJoggeur=$joggeurDuTheme->getId();
                    $tag->heartPoints=0;
                    $task->getTags()->add($tag);            
            }
            
            $form=$this->createForm(new TaskType(),$task);
             
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                  $joggeur=$em->getRepository('TdSMarathonBundle:Joggeur')
                             ->findOneBy(array('id' => $id));

                  $joggeur->getJoggeurScore()->setPointstogive($task->getRemainingPoints());
                  $em->persist($joggeur);
                  
                  $tags=$form->get('tags')->getData();
                  foreach($tags as $tag){
                            $idJoggeur=$tag->idJoggeur;
                            $joggeurHeart=$em->getRepository('TdSMarathonBundle:Joggeur')
                               ->findOneBy(array('id' => $idJoggeur));
                            $scoreJoggeurHeart=$em->getRepository('TdSMarathonBundle:JoggeurScore')
                               ->findJoggeurParTheme($joggeurHeart,$themePost);
                            $scoreJoggeurHeart=$scoreJoggeurHeart[0];
                            $scoresHeart=$scoreJoggeurHeart->getScores();
                            foreach($scoresHeart as $scoreHeart){
                               $scoreHeart->setHeartpoints($tag->heartPoints+$scoreHeart->getHeartpoints());
                               $scoreJoggeurHeart->setScore($scoreHeart);
                               $em->persist($scoreJoggeurHeart); 
                            }                                        
                  }
                  
                  $em->flush();
                  $em->clear();
                  $request->getSession()->getFlashBag()->add('notice',"Points bisous attribués avec succès");
                  // return $this->redirect($this->generateUrl('tds_marathon_joggeur_classement',array('saisonid'=>$saison->getId())));
                  return $this->redirectToRoute('tds_dashboard');
            }

            $form=$form->createView();

          }else{
            $scoreJoggeurParTheme="";
            $form="";
          }         

      	return $this->render('TdSMarathonBundle:Joggeur:addpoints.html.twig',array(
        	  		'joggeur'=>$joggeur,
                'joggeursDuTheme'=>$joggeursDuTheme,
        	  		'themePost'=>$themePost,
                'scoreJoggeurParTheme'=>$scoreJoggeurParTheme,
                'form'=>$form
  	  		));

      }else{
        $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
        return $this->redirectToRoute('tds_dashboard');
      }
    }
}

?>