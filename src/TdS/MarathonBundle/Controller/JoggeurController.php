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
    					   ->findAll();
        return $this->render('TdSMarathonBundle:Joggeur:index.html.twig', array(
        						'listeJoggeurs'=>$listeJoggeurs));
    }

    public function viewAction(Joggeur $joggeur, $id){
    	$em = $this->getDoctrine()->getManager();

        $saison=$em->getRepository('TdSMarathonBundle:Saison')
                    ->findOneBy(array('activate' => 1));

        $listeJoggeurs=$em->getRepository('TdSMarathonBundle:Joggeur')
                         ->findAll();

        $tabIdJoggeur=array();
        foreach($listeJoggeurs as $itemJoggeur){
            $tabIdJoggeur[]=$itemJoggeur->getId();
        }

    	$listeMusicTitles = $em
      			->getRepository('TdSMarathonBundle:MusicTitle')
      			->findBy(array('joggeur' => $joggeur))
   		;

        $joggeurScore = $em
                      ->getRepository('TdSMarathonBundle:JoggeurScore')
                      ->findJoggeurBySaison($saison, $joggeur);

        if($joggeurScore){
          $joggeurScore=$joggeurScore[0];  
        }
        
        $joggeur->setJoggeurScore($joggeurScore);


	    return $this->render('TdSMarathonBundle:Joggeur:view.html.twig', array(
          'tabIdJoggeur'=>$tabIdJoggeur,
	        'joggeur' => $joggeur,
            // 'joggeurScore' => $joggeurScore,
	    ));
	} 


	public function classementAction($saisonid, Request $request){
		$em=$this->getDoctrine()->getManager();
                     
      
        $listeJoggeurs=$em->getRepository('TdSMarathonBundle:Joggeur')
        				->findAll();

        $listeSaisons=$em->getRepository('TdSMarathonBundle:Saison')
                       ->findAll();


        $saison=$em->getRepository('TdSMarathonBundle:Saison')
                      ->findOneBy(array('id' => $saisonid));

        

        $listeJoggeursScore = $em
          ->getRepository('TdSMarathonBundle:JoggeurScore')
          ->findAllBySaison($saison);


        $tdsScoring = $this->container->get('tds_marathon.scoring');
        $listeJoggeursScore=$tdsScoring->sortScorebyTotal($listeJoggeursScore);

     

    	return $this->render('TdSMarathonBundle:Joggeur:classement.html.twig', array(
            'saison'=>$saison,
    				'listeJoggeurs'=>$listeJoggeurs,
            'listeSaisons'=>$listeSaisons,
            'listeJoggeursScore'=>$listeJoggeursScore,
        ));
	}





    public function addAction(Request $request){
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
    }

    

    public function editAction(Joggeur $joggeur, $id, Request $request){

    	$em=$this->getDoctrine()->getManager();	
    	$form = $this->createForm(new JoggeurEditType(), $joggeur);

    	$form->handleRequest($request);
	  	if ($form->isSubmitted() && $form->isValid()) {	
              $em->persist($joggeur); 		
	  		  $em->flush();
	  		  return $this->redirect($this->generateUrl('tds_marathon_joggeur_view',array('id'=>$joggeur->getId())));
	  	}else{
	  	
	  	return $this->render('TdSMarathonBundle:Joggeur:edit.html.twig',array(
	  		'form'=>$form->createView(),
	  		'joggeur'=>$joggeur,
	  		));
	  	}
	  	
    }


    public function deleteAction(Joggeur $joggeur, $id, Request $request){
        $em=$this->getDoctrine()->getManager();
        $joggeur = $em->getRepository('TdSMarathonBundle:Joggeur')
                    ->findOneBy(array('id' => $id));

        // $username=$joggeur->getUser()->getUsername();
        // echo "<h2>".$username."</h2>";

         // $user = $em->getRepository('TdSUserBundle:User')->find($id);

        if($joggeur!=null){
            $userAttached=$joggeur->getUser();
            if($userAttached != null){
               
               $userAttached->setJoggeur(null); 
               $em->persist($userAttached);
            }
             $em->remove($joggeur);
             $em->flush();
        }

        
        $listeJoggeurs=$em->getRepository('TdSMarathonBundle:Joggeur')
                           ->findAll();

        return $this->render('TdSMarathonBundle:Joggeur:index.html.twig', array(
                                'listeJoggeurs'=>$listeJoggeurs));
    }


    public function addpointsAction(Joggeur $joggeur, $id, Request $request){
    	$em=$this->getDoctrine()->getManager();

        $joggeur=$em->getRepository('TdSMarathonBundle:Joggeur')
                           ->findOneBy(array('id' => $id));

        $saison=$em->getRepository('TdSMarathonBundle:Saison')
                    ->findOneBy(array('activate' => 1));

    	$themePostActivate=$em->getRepository('TdSMarathonBundle:Theme')
    					   ->findOneBy(array('postActivate' => 1));

        $musicTitlesDuTheme=$themePostActivate->getMusicTitles();
        $joggeursDuTheme= new ArrayCollection();

        $scoreJoggeurParTheme=$em->getRepository('TdSMarathonBundle:JoggeurScore')
                           ->findJoggeurParTheme($joggeur,$themePostActivate);
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
              $joggeur->getJoggeurScore()->setPointstogive($task->getRemainingPoints()); 
              $em->persist($joggeur);
              
              $tags=$form->get('tags')->getData();
              foreach($tags as $tag){
                        $idJoggeur=$tag->idJoggeur;
                        $joggeurHeart=$em->getRepository('TdSMarathonBundle:Joggeur')
                           ->findOneBy(array('id' => $idJoggeur));
                        $scoreJoggeurHeart=$em->getRepository('TdSMarathonBundle:JoggeurScore')
                           ->findJoggeurParTheme($joggeurHeart,$themePostActivate);
                        $scoreJoggeurHeart=$scoreJoggeurHeart[0];
                        $scoresHeart=$scoreJoggeurHeart->getScores();
                        foreach($scoresHeart as $scoreHeart){
                           $scoreHeart->setHeartpoints($tag->heartPoints+$scoreHeart->getHeartpoints());
                           $scoreJoggeurHeart->setScore($scoreHeart);
                           $em->persist($scoreJoggeurHeart); 
                        }                                        
              }
              
              $em->flush();
              return $this->redirect($this->generateUrl('tds_marathon_joggeur_classement',array('saisonid'=>$saison->getId())));
        }

    	return $this->render('TdSMarathonBundle:Joggeur:addpoints.html.twig',array(
	  		'joggeur'=>$joggeur,
            'joggeursDuTheme'=>$joggeursDuTheme,
	  		'themePostActivate'=>$themePostActivate,
            'scoreJoggeurParTheme'=>$scoreJoggeurParTheme,
            'form'=>$form->createView()
	  		));
    }
}

?>