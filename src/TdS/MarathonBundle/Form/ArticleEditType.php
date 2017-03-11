<?php
namespace TdS\MarathonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleEditType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
	}

	public function getName(){
		return 'tds_marathon_article_edit';
	}

	public function getParent(){
		return new ArticleType();
	}
}


?>