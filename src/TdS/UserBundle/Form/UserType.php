<?php

namespace TdS\UserBundle\Form;

use TdS\UserBundle\Entity\User;
use TdS\UserBundle\Entity\Joggeur;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('joggeur', 'entity', array(
                    
                    'class'    => 'TdSMarathonBundle:Joggeur',
                    'property' => 'pseudo',
                    'multiple' => false,
                    'expanded' => false,
                    'empty_data'=>'',
                    'placeholder' => 'Selectionnez',
                  ))
            ->add('save','submit')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TdS\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tds_userbundle_user';
    }
}
