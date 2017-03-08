<?php
namespace TdS\CoreBundle\Controller;


use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\JoggeurScore;
use TdS\MarathonBundle\Entity\Theme;
use TdS\MarathonBundle\Entity\Saison;
use TdS\MarathonBundle\Entity\Website;
use TdS\MarathonBundle\Form\WebsiteType;
use TdS\MarathonBundle\Entity\MusicTitle;
use TdS\MarathonBundle\MP3File\TdSMP3File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TdS\CoreBundle\Form\ParticipateType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Session\Session;

class CoreController extends Controller{

	public function IndexAction(Request $request){
			$em = $this->getDoctrine()->getManager();



			$listeSaisons=$em->getRepository('TdSMarathonBundle:Saison')
						->findAllSaisonsWithThemes();


			$tdsSaison = $this->container->get('tds_marathon.saison');
	        $saison=$tdsSaison->getCurrSaison();


	        $saison=$em->getRepository('TdSMarathonBundle:Saison')
                   ->findSaisonWithThemes($saison->getId());

            $listeDernThemes=$em->getRepository('TdSMarathonBundle:Theme')
            					->findDerniersThemes(5);


	      	$musicTitles=$em->getRepository('TdSMarathonBundle:MusicTitle')
	      			->findAllBySaison($saison);

	      	shuffle($musicTitles);

	      	$listeArticles=$em->getRepository('TdSMarathonBundle:Article')
	      					  ->findSeveral(3,0);

	      	
	      	$websites=$em->getRepository('TdSMarathonBundle:Website')
    						->findAll();

	      	$website=new Website();
			$formWebsite=$this->createForm(new WebsiteType(),$website,array(
					'method' => 'POST',
    				'action' => '#anchorLiens'
			));

			
  	
			if($formWebsite->handleRequest($request)->isValid()){
				$em=$this->getDoctrine()->getManager();
				$em->persist($website);
				$em->flush();
				return $this->redirect($this->generateUrl('tds_home'). '#anchorLiens');
			}
			

			$listeJoggeurs=$em
			      			->getRepository('TdSMarathonBundle:Joggeur')
			      			->findAllSortByLastLogin();


	        $tdsScoring = $this->container->get('tds_marathon.scoring');
	        $listeJoggeursScore=$tdsScoring->getAllJoggeursScoresOfSaison($saison);
	        


			return $this->get('templating')->renderResponse('TdSCoreBundle:Core:index.html.twig', array(
					'listeSaisons'=>$listeSaisons,
					'saison'=>$saison,
					'listeDernThemes'=>$listeDernThemes,						
					'listeArticles'=>$listeArticles,
					'listeJoggeurs'=>$listeJoggeurs,
					'listeJoggeursScore'=>$listeJoggeursScore,
					"websites"=>$websites,
					"musicTitles"=>$musicTitles,
					'formWebsite'=>$formWebsite->createView()			
			));			 
	}





	public function DashboardAction(Request $request){
		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') || $this->get('security.context')->isGranted('ROLE_USER')) {
			$em = $this->getDoctrine()->getManager();

			$tdsSaison = $this->container->get('tds_marathon.saison');
	        $saison=$tdsSaison->getCurrSaison();		
	      	
	      	

	      	$allThemes=$em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findAllThemes();

	      	$listeArticles=$em->getRepository('TdSMarathonBundle:Article')
	      					  ->findSeveral(4,0);

	      	
	      	$websites=$em->getRepository('TdSMarathonBundle:Website')
							->findAll();

			$website=new Website();
			$formWebsite=$this->createForm(new WebsiteType(),$website,array(
					'method' => 'POST',
    				'action' => '#anchorLiens'
			));



			$tdsTheme = $this->container->get('tds_marathon.theme');
			
			$theme = $em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findOneThemeByStatut(1);

			if($theme){
        		$joggeurForDraftmodeTheme=$tdsTheme->getJoggeurForDraftmodeTheme($theme);
        	}else{
        		$joggeurForDraftmodeTheme=null;
        	}

  	
			if($formWebsite->handleRequest($request)->isValid()){
				$em=$this->getDoctrine()->getManager();
				$em->persist($website);
				$em->flush();
				return $this->redirect($this->generateUrl('tds_home'). '#anchorLiens');
			}

			$user=$this->getUser();
			// $joggeurUser=$user->getJoggeur();
			$joggeur=$em->getRepository('TdSMarathonBundle:Joggeur')
					->findJoggeurByUser($user);


			if(!empty($saison)){
				$joggeurScore = $em
		          ->getRepository('TdSMarathonBundle:JoggeurScore')
		          ->findJoggeurBySaison($saison, $joggeur);
		        
				if(empty($joggeurScore)){
					$joggeurScore=new JoggeurScore;
				}
			}else{
				$joggeurScore=new JoggeurScore;
			}			        


			
				      
			return $this->get('templating')->renderResponse('TdSCoreBundle:Core:indexAdmin.html.twig', array(
					'saison'=>$saison,
					'theme'=>$theme,
					'joggeur'=>$joggeur,
					'listeArticles'=>$listeArticles,
					'joggeurScore'=>$joggeurScore,
					'allThemes'=>$allThemes,
					'joggeurForDraftmodeTheme'=>$joggeurForDraftmodeTheme,
					'websites'=>$websites,
					'formWebsite'=>$formWebsite->createView()											
			));
		}
	}


	public function participateAction(Request $request){
		$form = $this->get('form.factory')->create(new ParticipateType());
		
		
        if ($request->getMethod() == 'POST'){
			$form->handleRequest($request);
            $data = $form->getData();

            // $contenuMail="<div>
            // 				<h2>“".$data['pseudo']."“ veut devenir joggeur !</h2>
            // 				<h3>Mail : </h3>
            // 				<span>".$data['email']."</span>
            // 				<h3>Candidature :</h3>
            // 				<p>".$data['content']."</p>
            // 			 </div>";

           $contenuMail="yo";

            $message = \Swift_Message::newInstance()
            // $message = \Swift_Message::newInstance('SSL0.OVH.NET:contact@tiretdusix.net',25)
                // ->setContentType('text/html')
                ->setSubject("Marathon de la Semaine : demande d'inscription")
                ->setFrom($data['email'])
                ->setTo('kl6yranne@yahoo.fr')
                ->setBody($contenuMail);

            $mailer=$this->get('mailer');

            $mailer->send($message);


            return $this->render('TdSCoreBundle:Core:participate-validation.html.twig',array('data'=>$data));
        }

		return $this->render('TdSCoreBundle:Core:participate.html.twig',array(
				'form'=>$form->createView()));
		
	}

	public function creditsAction(){
		return $this->render('TdSCoreBundle:Core:credits.html.twig');
	}

	public function kilometrageAction(){
		$em = $this->getDoctrine()->getManager();
		$duree=$em
	      			->getRepository('TdSMarathonBundle:MusicTitle')
	      			->findAllDurations();

	    // $allDurations=0; 			
	    // foreach($allMusicTitles as $musicTitle){
	    // 	if($musicTitle->getDuration()!=null){
	    // 		$allDurations=$allDurations+$musicTitle->getDuration();
	    // 	}
	    // }

	    // $mp3File=new TdSMP3File(0);
	    // $kilometrage=round(($allDurations*10)/3600,2);
	    // $formatDuration=$mp3File->formatTime($allDurations);


// array(
// 			'kilometrage'=>$kilometrage,
// 			'formatDuration'=>$formatDuration
// 			)
		return $this->render('TdSCoreBundle:Core:kilometrage.html.twig',array("duree"=>$duree));
	}	
}