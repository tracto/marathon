<?php
namespace TdS\CoreBundle\Controller;


use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\JoggeurScore;
use TdS\MarathonBundle\Entity\Theme;
use TdS\MarathonBundle\Entity\Saison;
use TdS\MarathonBundle\Entity\Website;
use TdS\MarathonBundle\Form\WebsiteType;
use TdS\MarathonBundle\Form\HotfreshType;
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

			$tdsScoring = $this->container->get('tds_marathon.scoring');
			$tdsSaison = $this->container->get('tds_marathon.saison');

			$listeSaisons=$em->getRepository('TdSMarathonBundle:Saison')
						->findAllSaisonsWithThemes();

	        $saison=$tdsSaison->getCurrSaison();


	        $saison=$em->getRepository('TdSMarathonBundle:Saison')
                   ->findSaisonWithThemes($saison->getId());

            $listeDernThemes=$em->getRepository('TdSMarathonBundle:Theme')
            					->findDerniersThemes(8);


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
			$tdsTheme = $this->container->get('tds_marathon.theme');
	      	$tdsScoring = $this->container->get('tds_marathon.scoring');

	        $saison=$tdsSaison->getCurrSaison();

	      	
	      	$saison=$em->getRepository('TdSMarathonBundle:Saison')
                   ->findSaisonWithThemes($saison->getId());
            $saisonCurrent=$saison;

	      	
	        $listeJoggeursScore=$tdsScoring->getAllJoggeursScoresOfSaison($saison);


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
			

			if(!empty($saison)){
		        $joggeurScore=$tdsScoring->getAllSaisonScoresOfJoggeurScore($saison, $user->getJoggeur());
				if(empty($joggeurScore)){
					$joggeurScore=new JoggeurScore;
				}
			}else{
				$joggeurScore=new JoggeurScore;
			}

			
			
	        $i=0;
	        $placeClassement=0;
	        foreach($listeJoggeursScore as $joggeurScoreItem){
	        	$i++;
	        	if($joggeurScoreItem->getId() == $joggeurScore->getId()){
	        		$placeClassement=$i;
	        	}
	        }			        


			
				      
			return $this->get('templating')->renderResponse('TdSCoreBundle:Core:indexAdmin.html.twig', array(
					'saison'=>$saison,
					'theme'=>$theme,
					'listeArticles'=>$listeArticles,					
					'placeClassement'=>$placeClassement,
					'allThemes'=>$allThemes,
					'joggeurForDraftmodeTheme'=>$joggeurForDraftmodeTheme,
					'listeJoggeursScore'=>$listeJoggeursScore,
					'joggeurScoreSaison'=>$joggeurScore,
					'websites'=>$websites,
					'formWebsite'=>$formWebsite->createView()											
			));
		}
	}


	public function ShowHotfreshAction(Request $request){
		$em = $this->getDoctrine()->getManager();
		$hotfresh = $em
		    ->getRepository('TdSMarathonBundle:Hotfresh')
		    ->findOneById(array('id',1));
		return $this->render('TdSMarathonBundle:Hotfresh:show.html.twig',array('hotfresh'=>$hotfresh));
	}

	
	public function EditHotfreshAction(Request $request,$id){
		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
			$em = $this->getDoctrine()->getManager();
			$hotfresh=$em->getRepository('TdSMarathonBundle:Hotfresh')
						->find($id);

			$form = $this->createForm(new HotfreshType(),$hotfresh);

		
			$form->handleRequest($request);
			
  	  		if ($form->isSubmitted() && $form->isValid()) {	
				$em=$this->getDoctrine()->getManager();
				$em->persist($hotfresh);
				$em->flush();
				
				$request->getSession()->getFlashBag()->add('notice',"Message d'accueil modifié!");

			}
			return $this->render('TdSMarathonBundle:Hotfresh:form.html.twig',array('form'=>$form->createView()));
		}
	}


	public function participateAction(Request $request){
		$form = $this->get('form.factory')->create(new ParticipateType());
		
		
        if ($request->getMethod() == 'POST'){
			$form->handleRequest($request);
            $data = $form->getData();

           $contenuMail="yo";

            $message = \Swift_Message::newInstance()
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
		$infosMusicTitle=$em->getRepository('TdSMarathonBundle:MusicTitle')
	      			->findAllDurations();
	      			
	    $kilometres=round($infosMusicTitle['kilometrage'],2);
	    $duree=date('H\h i\m\i\n', $infosMusicTitle['temps']);

		return $this->render('TdSCoreBundle:Core:kilometrage.html.twig',array("duree"=>$duree,
			"kilometres"=>$kilometres));
	}
}
    public function chatAction() {
        return $this->render('TdSCoreBundle:Core:chat.html.twig');
    }

}

