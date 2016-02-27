<?php

namespace TdS\MarathonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ->add('activate','checkbox',array('required'=>false))
            ->add('save','submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
