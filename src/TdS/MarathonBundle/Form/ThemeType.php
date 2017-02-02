<?php

namespace TdS\MarathonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ThemeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre','text')
            ->add('saison', 'entity', array(
                    'class'    => 'TdSMarathonBundle:Saison',
                    'property' => 'titre',
                    'multiple' => false,
                    'expanded' => false
            ))
            ->add('dateDebut','datetime',array(
                      'date_widget' => 'single_text',
                      'input' => 'datetime',
                      'format' => 'MM/dd/yyyy',
                      'attr' => [
                         'class' => 'form-control date',
                         // 'data-provide' => 'datepicker',
                         
                        ]
                    )                     
                )
            ->add('dateFin','datetime',array(
                      'date_widget' => 'single_text',
                      'input' => 'datetime',
                      'format' => 'MM/dd/yyyy',
                      'attr' => [
                         'class' => 'form-control date',                        
                        ]
                    )                     
                )
            ->add('image',new ImageType(),array('required'=>false))
            ->add('valider','submit');

            if($options['draftmode'] == null){
                 $builder->add('activate','checkbox',array('required'=>false))
                         ->add('joggeur', 'entity', array(
                                'class'    => 'TdSMarathonBundle:Joggeur',
                                'property' => 'pseudo',
                                'multiple' => false,
                                'expanded' => false
                            ))
                         ->add('chronique','ckeditor',array('required'=>false))
                         ->add('joggeurChronique', 'entity', array(
                                'placeholder' => 'Choisir un joggeur',
                                'class'    => 'TdSMarathonBundle:Joggeur',
                                'property' => 'pseudo',
                                'multiple' => false,
                                'expanded' => false,
                                'required' => false
                            ));
            }else{
                $builder->remove('activate')
                        ->remove('joggeur')
                        ->remove('dateDebut')
                        ->remove('dateFin')
                        ->remove('saison')
                        ->remove('chronique')
                        ->remove('joggeurChronique');
            }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,    // 1er argument : L'évènement qui nous intéresse : ici, PRE_SET_DATA
            function(FormEvent $event) { // 2e argument : La fonction à exécuter lorsque l'évènement est déclenché
               $theme = $event->getData();

                if (null === $theme) {
                  return; // On sort de la fonction sans rien faire lorsque $advert vaut null
                }

                $form = $event->getForm();
               
                    if($theme->getDraftmode() == 1){
                            $event->getForm()->remove('activate')
                                ->remove('joggeur')
                                ->remove('chronique')
                                ->remove('dateDebut')
                                ->remove('dateFin')
                                ->remove('saison')
                                ->remove('joggeurChronique');
                    }
                

            }
        );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TdS\MarathonBundle\Entity\Theme',
            'draftmode' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tds_marathonbundle_theme';
    }
}
