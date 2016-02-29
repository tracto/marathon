<?php

namespace TdS\MarathonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MusicTitleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre','text')
            ->add('file', 'file')
            ->add('joggeur', 'entity', array(
                    'class'    => 'TdSMarathonBundle:Joggeur',
                    'property' => 'pseudo',
                    'multiple' => false,
                    'expanded' => false,
                    'is_granted' => 'ROLE_ADMIN'
                  ))
            ->add('theme', 'entity', array(
                    'class'    => 'TdSMarathonBundle:Theme',
                    'property' => 'titre',
                    'multiple' => false,
                    'expanded' => false,
                    'is_granted' => 'ROLE_ADMIN'
                  ))
            ->add('valider','submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TdS\MarathonBundle\Entity\MusicTitle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tds_marathonbundle_musictitle';
    }
}
