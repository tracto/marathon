<?php

namespace TdS\MarathonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaisonType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre','text')
            // ->add('statut','checkbox',array('required'=>false))
            ->add('statut', 'choice', array(
                'choices'  => array(
                    'Pas encore active' => 0,
                    'Saison en cours' => 1,
                    'Saison terminée' => 2,
                ),
                'choices_as_values' => true,
            ))
            ->add('image',new ImageType(),array('required'=>false))
            ->add('bilan','ckeditor',array('required'=>false))
            ->add('save','submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TdS\MarathonBundle\Entity\Saison'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tds_marathonbundle_saison';
    }
}
