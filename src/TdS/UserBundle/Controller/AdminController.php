<?php

namespace TdS\UserBundle\Controller;

use TdS\UserBundle\Entity\User;
use TdS\UserBundle\Form\UserType;
use TdS\UserBundle\Form\UserEditType;
use TdS\MarathonBundle\Entity\Joggeur;
use TdS\MarathonBundle\Form\JoggeurType;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

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

    
   	public function registerAction(Request $request)
    {

    	$userManager = $this->get('fos_user.user_manager');
		$users = $userManager->findUsers();
		
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                // $response = new RedirectResponse($url);
            }

            // $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $this->render('TdSUserBundle:Admin:index.html.twig', array(
        	'users'=>$users));
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
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