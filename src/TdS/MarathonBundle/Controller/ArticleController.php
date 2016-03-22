<?php

namespace TdS\MarathonBundle\Controller;


use AppBundle\Entity\Task;
use AppBundle\Entity\Tag;
use AppBundle\Form\Type\TaskType;
use TdS\MarathonBundle\Entity\Article;
use TdS\MarathonBundle\Form\ArticleType;
use TdS\MarathonBundle\Form\ArticleEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;




class ArticleController extends Controller{

    public function indexAction(){

	}

	public function addAction(Request $request){
		$article=new Article();
		$form=$this->get('form.factory')->create(new ArticleType(), $article); 
		$form->handleRequest($request);

		if($form->isValid()){

			$em=$this->getDoctrine()->getManager();

			$article->setDatePost(new \DateTime("now"));
			
			$em->persist($article);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice','article bien enregistré.');

			// return $this->redirect($this->generateUrl('tds_marathon_article_view',array('id'=>$article->getId())));
		}

        return $this->render('TdSMarathonBundle:Article:add.html.twig', array(
        	'form'=>$form->createView()
        	));
	}
}

?>