<?php
namespace TdS\CoreBundle\Controller;


use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\JoggeurScore;
use TdS\MarathonBundle\Entity\Theme;
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

class CoreController extends Controller{

	public function IndexAction(Request $request){
			$em = $this->getDoctrine()->getManager();

			$saison=$em
	      			->getRepository('TdSMarathonBundle:Saison')
	      			->findOneBy(array('activate' => 1));

	      	$lastSaison=$em
	      			->getRepository('TdSMarathonBundle:Saison')
	      			->findLastOne();
	      	$lastSaison=$lastSaison[0];		
	      	// var_dump($lastSaison);
			
	    	$theme = $em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findOneBy(array('activate' => 1));


	      	$themePost = $em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findOneBy(array('postActivate' => 1));


	      	$allThemes=$em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findAll();

	      	$listeArticles=$em->getRepository('TdSMarathonBundle:Article')
	      					  ->findSeveral(3);

	      	
	      	$websites=$em->getRepository('TdSMarathonBundle:Website')
    						->findAll();

	      	
			

			$listeJoggeurs=$em
			      			->getRepository('TdSMarathonBundle:Joggeur')
			      			->findAll();

			$listeJoggeursScore = $em
		          ->getRepository('TdSMarathonBundle:JoggeurScore')
		          ->findAllBySaison($saison);


	        $tdsScoring = $this->container->get('tds_marathon.scoring');
	        $listeJoggeursScore=$tdsScoring->sortScorebyTotal($listeJoggeursScore);

			

			return $this->get('templating')->renderResponse('TdSCoreBundle:Core:index.html.twig', array(
					'saison'=>$saison,
					'theme'=>$theme,							
					'allThemes'=>$allThemes,							
					'themePost'=>$themePost,
					'listeArticles'=>$listeArticles,
					'listeJoggeurs'=>$listeJoggeurs,
					'listeJoggeursScore'=>$listeJoggeursScore,
					"websites"=>$websites			
			));
			
			 
	}


	public function DashboardAction(Request $request){
		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') || $this->get('security.context')->isGranted('ROLE_USER')) {
			$em = $this->getDoctrine()->getManager();

			$saison=$em
	      			->getRepository('TdSMarathonBundle:Saison')
	      			->findOneBy(array('activate' => 1));

	      	$lastSaison=$em
	      			->getRepository('TdSMarathonBundle:Saison')
	      			->findLastOne();
	      	$lastSaison=$lastSaison[0];		
	      	// var_dump($lastSaison);
			
	    	$theme = $em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findOneBy(array('activate' => 1));


	      	$themePost = $em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findOneBy(array('postActivate' => 1));


	      	$allThemes=$em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findAll();

	      	$listeArticles=$em->getRepository('TdSMarathonBundle:Article')
	      					  ->findSeveral(3);

	      	
	      	$websites=$em->getRepository('TdSMarathonBundle:Website')
							->findAll();

		
	   		$draftmodeTheme = $em
	      			->getRepository('TdSMarathonBundle:Theme')
	      			->findOneBy(array('draftmode' => 1));
	      	if(!empty($theme)){
		   		$musicTitle10th = $em
		      			->getRepository('TdSMarathonBundle:MusicTitle')
		      			->getDixieme($theme);
		      	$timeGap=$theme->getTimeGap($theme->getDateFin());
	      	}else if(!empty($themePost)){
	      		$musicTitle10th = $em
		      			->getRepository('TdSMarathonBundle:MusicTitle')
		      			->getDixieme($themePost);
	      		$timeGap=null;
	      	}else{
	      		$timeGap=null;
	      	}

			if(!empty($musicTitle10th)){
				$musicTitle10th=$musicTitle10th[0];
			}else{
				$musicTitle10th=null;
			}

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

			$user=$this->getUser();
			$joggeur=$user->getJoggeur();

			if(!empty($saison)){
				$joggeurScore = $em
		          ->getRepository('TdSMarathonBundle:JoggeurScore')
		          ->findJoggeurBySaison($saison, $joggeur);

		        if(!empty($joggeurScore)){
					$joggeurScore=$joggeurScore[0];
				}else{
					$joggeurScore=new JoggeurScore;
				}
			}else{
				$joggeurScore=new JoggeurScore;
			}			        


			
				      
			return $this->get('templating')->renderResponse('TdSCoreBundle:Core:indexAdmin.html.twig', array(
					'saison'=>$saison,
					'lastSaison'=>$lastSaison,
					'theme'=>$theme,
					'listeArticles'=>$listeArticles,
					'timeGap'=>$timeGap,
					'joggeurScore'=>$joggeurScore,
					'draftmodeTheme'=>$draftmodeTheme,
					'allThemes'=>$allThemes,
					'musicTitle10th'=>$musicTitle10th,
					'themePost'=>$themePost,
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

            $contenuMail="<div>
            				<h2>“".$data['pseudo']."“ veut devenir joggeur !</h2>
            				<h2>Mail : </h2>
            				<span>".$data['email']."</span><br/>
            				<h2>Candidature :</h2>
            				<span>".$data['content']."</span><br/>
            			 </div>";

            $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject("Marathon : demande d'inscription")
                ->setFrom($data['email'])
                ->setTo('contact@tiretdusix.net')
                ->setBody($contenuMail);

            $this->get('mailer')->send($message);

            return $this->render('TdSCoreBundle:Core:participate-validation.html.twig',array('data'=>$data));
        }

		return $this->render('TdSCoreBundle:Core:participate.html.twig',array(
				'form'=>$form->createView()));
		
	}

	public function kilometrageAction(){
		$em = $this->getDoctrine()->getManager();
		$allMusicTitles=$em
	      			->getRepository('TdSMarathonBundle:MusicTitle')
	      			->findAll();

	    $allDurations=0; 			
	    foreach($allMusicTitles as $musicTitle){
	    	if($musicTitle->getDuration()!=null){
	    		$allDurations=$allDurations+$musicTitle->getDuration();
	    	}
	    }

	    $mp3File=new TdSMP3File(0);
	    $kilometrage=round(($allDurations*10)/3600,2);
	    $formatDuration=$mp3File->formatTime($allDurations);

		return $this->render('TdSCoreBundle:Core:kilometrage.html.twig',array(
			'kilometrage'=>$kilometrage,
			'formatDuration'=>$formatDuration
			));
	}	
}