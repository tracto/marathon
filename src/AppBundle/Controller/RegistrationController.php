<?php
// src/AppBundle/Controller/RegistrationController.php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
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
                $response = new RedirectResponse($url);
            }

            // $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}

?>