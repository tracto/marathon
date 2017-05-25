<?php

namespace TdS\UserBundle\Controller;

use TdS\UserBundle\Entity\User;
use TdS\UserBundle\Form\UserType;
use TdS\UserBundle\Form\UserEditType;
use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Form\JoggeurType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends Controller
{

	public function menuAction(){	    
	    return $this->render('TdSMarathonBundle:Default:menu.html.twig');
	}
	
    public function indexAction(Request $request){

		$userManager = $this->get('fos_user.user_manager');
		$users = $userManager->findUsers();

		$em=$this->getDoctrine()->getManager();
    	$joggeurs = $em->getRepository('TdSMarathonBundle:Joggeur')->findAllJoggeursWithUsers();


        return $this->render('TdSUserBundle:Admin:index.html.twig', array(
        	'users'=>$users));
    	}

    


	public function editAction(User $user,$id, Request $request){
	    	$em=$this->getDoctrine()->getManager();
		  	
		  	$formUser=$this->createForm(new UserEditType(),$user);
		  	
		  	if($formUser->handleRequest($request)->isValid()){

		  		$em->flush();
		  		$request->getSession()->getFlashBag()->add('notice','utilisateur bien modifié.');
		  		return $this->redirect($this->generateUrl('tds_admin_home'));

		  	}

		  	return $this->render('TdSUserBundle:Admin:edit.html.twig',array(
		  		'formUser'=>$formUser->createView(),
		  		'user'=>$user));
	}



	public function deleteAction(User $user,$id, Request $request){
		$em=$this->getDoctrine()->getManager();
		$user = $em->getRepository('TdSUserBundle:User')->find($id);

		if($user!=null){
			$em->remove($user);
        	$em->flush();
		}

		$userManager = $this->get('fos_user.user_manager');

		$users = $userManager->findUsers();

		return $this->render('TdSUserBundle:Admin:index.html.twig', array(
        	'users'=>$users));
	}

}