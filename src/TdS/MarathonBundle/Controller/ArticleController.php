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


	public function moreAction(Request $request,$offset){
		$em=$this->getDoctrine()->getManager();

		$listeArticles=$em->getRepository('TdSMarathonBundle:Article')
    					 ->findSeveral(4,$offset);


    	return $this->render('TdSMarathonBundle:Article:more.html.twig',array(
	    						'listeArticles'=>$listeArticles
		  				  ));
	}



	public function addAction(Request $request){
		$article=new Article();
		$form=$this->get('form.factory')->create(new ArticleType(), $article); 
		$form->handleRequest($request);

		if($form->isValid()){
			$targetPath = $request->request->get('_target_path');
			$em=$this->getDoctrine()->getManager();

			$article->setDatePost(new \DateTime("now"));
			
			$em->persist($article);
			$em->flush();

			$request->getSession()->getFlashBag()->add('notice','article bien enregistré.');

			if($targetPath != null){
                return $this->redirect($targetPath);
            }
			// return $this->redirect($this->generateUrl('tds_marathon_article_view',array('id'=>$article->getId())));
		}

        return $this->render('TdSMarathonBundle:Article:add.html.twig', array(
        	'form'=>$form->createView()
        	));
	}


	public function editAction(Request $request, Article $article, $id){
		$em=$this->getDoctrine()->getManager();

		$form=$this->createForm(new ArticleEditType(),$article);

	  	if($form->handleRequest($request)->isValid()){
	  		$targetPath = $request->request->get('_target_path');
	  		$em->flush();
	  		$request->getSession()->getFlashBag()->add('notice','Article bien modifié.');
	  		if($targetPath != null){
                return $this->redirect($targetPath);
            }
	  		//return $this->redirect($this->generateUrl('tds_marathon_theme_view',array('id'=>$theme->getId())));

	  	}else{

	  	return $this->render('TdSMarathonBundle:Article:edit.html.twig',array(
	  		'form'=>$form->createView(),
	  		'article'=>$article));
	  	}

	}


	public function deleteAction(Article $article, $id, Request $request){
		$referer = $this->getRequest()->headers->get('referer');

        $em=$this->getDoctrine()->getManager();
        

        if($article!=null){
             $em->remove($article);
             $em->flush();
        }

        

        return $this->redirect($referer);
    }




	
}

?>