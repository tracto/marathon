<?php

namespace TdS\MarathonBundle\Controller;

use TdS\MarathonBundle\Entity\Theme;
use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Entity\MusicTitle;
use TdS\MarathonBundle\Form\MusicTitleType;
use TdS\MarathonBundle\Form\MusicTitleEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
/*use JMS\SerializerBundle\JMSSerializerBundle;*/
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class MusicTitleController extends Controller{

	public function listAction($theme_id, Request $request){
		if($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
			 $em=$this->getDoctrine()->getManager();

			 $theme = $em->getRepository('TdSMarathonBundle:Theme')
			 			 ->findOneThemeById($theme_id);
		      			 

			 // $listeMusicTitles=$em->getRepository('TdSMarathonBundle:MusicTitle')
		  //          			      ->findBy(array('theme' => $theme));

		    return $this->render('TdSMarathonBundle:MusicTitle:liste.html.twig', array(
	        							'theme'=>$theme,
	        							 // 'listeMusicTitles'=>$listeMusicTitles
	        							));
	    }else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}
	}

	

	public function addAction($theme_id, $joggeur_id,  $route, Request $request){
		$em=$this->getDoctrine()->getManager();
		$theme=new Theme();
		$joggeur=new Joggeur();
		$musicTitle=new MusicTitle();

		if($joggeur_id){
			$joggeur = $em->getRepository('TdSMarathonBundle:Joggeur')
		      			->findJoggeurById($joggeur_id);
		}

		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') || ($this->get('security.context')->isGranted('ROLE_USER')) && $this->getUser() == $joggeur->getUser() ){
			

			if($route == 'joggeur-view'){
				$url=$this->generateUrl('tds_marathon_joggeur_view',array('id'=>$joggeur_id));

			}elseif($route == 'index'){
				$url=$this->generateUrl('tds_home');
			}elseif($route == 'theme-view'){
				$url=$this->generateUrl('tds_marathon_theme_view',array('id'=>$theme_id));
			}else{
				$url=$this->generateUrl('tds_home');
			}

			
			if($theme_id){
				$theme = $em
		      			->getRepository('TdSMarathonBundle:Theme')
		      			->findOneThemeById($theme_id);
		      	$musicTitle->setTheme($theme);
		   	}

			if($joggeur_id){
		      	$musicTitle->setJoggeur($joggeur);
	   		}



		    $form=$this->get('form.factory')->create(new MusicTitleType(), $musicTitle); 
			$form->handleRequest($request);

			if($form->isValid()){

				$em=$this->getDoctrine()->getManager();
				$em->persist($musicTitle);
				$em->flush();
				
				$request->getSession()->getFlashBag()->add('notice','morceau bien uploadé.');
				return $this->redirect($url);
			}

	        return $this->render('TdSMarathonBundle:MusicTitle:add.html.twig', array(
	        							'theme'=>$theme,
	        							'joggeur'=>$joggeur,
	        							'form'=>$form->createView()
	        ));

        }else{
		    	$request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    	return $this->redirectToRoute('tds_dashboard');
		}
	}



	public function addjsAction($theme_id, $joggeur_id, Request $request){
		$em=$this->getDoctrine()->getManager();
		$theme=new Theme();
		$joggeur=new Joggeur();
		$musicTitle=new MusicTitle();

		if($joggeur_id){
			$joggeur = $em->getRepository('TdSMarathonBundle:Joggeur')
		      			->findJoggeurById($joggeur_id);
		}

		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN') || ($this->get('security.context')->isGranted('ROLE_USER')) && $this->getUser() == $joggeur->getUser() ){
			
			
			if($theme_id){
				$theme = $em
		      			->getRepository('TdSMarathonBundle:Theme')
		      			->findOneThemeById($theme_id);
		      	$musicTitle->setTheme($theme);
		   	}

			if($joggeur_id){
		      	$musicTitle->setJoggeur($joggeur);
	   		}

	   		/*$filedata = json_decode($this->getRequest()->get('filedata'));*/
	   		$formdata = $request->request->get('formdata',null);

		    $form=$this->get('form.factory')->create(new MusicTitleType(), $formdata); 
			$form->handleRequest($request);

			if($form->isValid()){

				/*$em=$this->getDoctrine()->getManager();
				$em->persist($musicTitle);
				$em->flush();
				
				$request->getSession()->getFlashBag()->add('notice','morceau bien uploadé.');
				return $this->redirect($url);*/
			}

	        /*return $this->render('TdSMarathonBundle:MusicTitle:add.html.twig', array(
	        							'theme'=>$theme,
	        							'joggeur'=>$joggeur,
	        							'form'=>$form->createView()
	        ));*/

	        $response = new JsonResponse();
			

			$response->setData(array(
				"theme_id"=>$theme_id,
				"joggeur_id"=>$joggeur_id,
				"formdata"=>$formdata,
				'form'=>$form
			)
			);

			
			return $response;

        }else{
		    	$request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    	/*return $this->redirectToRoute('tds_dashboard');*/
		}
	}


	public function editAction(Request $request, $id, MusicTitle $musicTitle){
    	$em=$this->getDoctrine()->getManager();

    	$musicTitle=$em->getRepository('TdSMarathonBundle:MusicTitle')
	    			->findOneMusictitleById($id);


    	if($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
	    			  	
		  	$form=$this->get('form.factory')->create(new MusicTitleEditType(), $musicTitle); 
			$form->handleRequest($request);

			if($form->isValid()){

		  		$em->flush();
		  		$request->getSession()->getFlashBag()->add('notice','Morceau bien modifié.');
		  
		  		return $this->redirect($this->generateUrl('tds_marathon_musictitle_list',array('id'=>$musicTitle->getTheme()->getId())));

		  	}else{

		  	return $this->render('TdSMarathonBundle:MusicTitle:edit.html.twig',array(
		  		'form'=>$form->createView(),
		  		'musicTitle'=>$musicTitle));
		  	}

		}else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}
    }


	


	public function reorderAction(){
		if($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
			$em=$this->getDoctrine()->getManager();

			$theme_id = $this->getRequest()->get('theme_id');

			$musictitlesTabNew = json_decode($this->getRequest()->get('musictitlesTabNew'));

			$theme = $em->getRepository('TdSMarathonBundle:Theme')
			 			 ->findOneThemeById($theme_id);

			$listeMusictitlesInit=$theme->getMusicTitles();

			$datesInitTab=array();

			foreach($listeMusictitlesInit as $musictitleinit){
				array_push($datesInitTab,$musictitleinit->getDateUpload());
			}

			$musicTitle=new MusicTitle();
			$i=0;
			foreach($musictitlesTabNew as $musicTitleNew){
				$musicTitle = $em->getRepository('TdSMarathonBundle:MusicTitle')
							->find($musicTitleNew);
				$musicTitle->setDateUpload($datesInitTab[$i]);
				var_dump($musicTitle->getDateUpload());
				$em->persist($theme);
				$em->flush();
				$i++;
			}


			

			$response = new JsonResponse();
			

			$response->setData(array(
				"theme_id"=>$theme_id,
				"new order"=>$musictitlesTabNew
			)
			);

			
			return $response;

		}else{
		    $request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    return $this->redirectToRoute('tds_dashboard');
		}
	}
	


	public function deleteAction(MusicTitle $musicTitle, $id, Request $request){
		if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')){
		$referer = $this->getRequest()->headers->get('referer');

		$em=$this->getDoctrine()->getManager();

        if($musicTitle!=null){
             $em->remove($musicTitle);
             $em->flush();
             $request->getSession()->getFlashBag()->add('notice',"Morceau supprimé");
        }

        return $this->redirect($referer);

        }else{
		    	$request->getSession()->getFlashBag()->add('notice',"tu n'as pas le droit d'effectuer cette action.");
		    	return $this->redirectToRoute('tds_dashboard');
		}
	}
}
?>