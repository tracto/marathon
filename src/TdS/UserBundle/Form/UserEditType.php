<?php
namespace TdS\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserEditType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		// $builder->remove('date');
	}

	public function getName(){
		return 'tds_admin_user_edit';
	}

	public function getParent(){
		return new UserType();
	}
}


?>