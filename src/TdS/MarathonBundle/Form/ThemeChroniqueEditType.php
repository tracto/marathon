<?php
namespace TdS\MarathonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ThemeChroniqueEditType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
	}

	public function getName(){
		return 'tds_marathon_theme_edit_chronique';
	}

	public function getParent(){
		return new ThemeChroniqueType();
	}
}


?>