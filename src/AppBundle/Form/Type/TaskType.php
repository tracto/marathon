<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $option){
		$builder->add('remainingPoints','hidden')
				->add('tags','collection',array('type'=>new TagType()))
				->add('valider','submit');
	}

	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults(array(
			'data_class'=>'AppBundle\Entity\Task',

			));
	}

	public function getName(){
		return 'task';
	}
}