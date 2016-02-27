<?php
namespace TdS\MarathonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatepickerType extends AbstractType
{


  public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('dateDebut','datetime',array(
                      'widget' => 'single_text',
                      'input' => 'datetime',
                      'format' => 'dd/MM/yyyy HH:mm',
                      'attr' => [
                         'class' => 'form-control input-inline datepicker',
                         'data-provide' => 'datepicker',
                         'data-date-format' => 'yyyy-mm-dd hh:mm'
                        ]
                    )                     
                )
        ;
    }

  
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    // $resolver->setDefaults(array(
    //   'attr' => array('class' => 'datepicker') // On ajoute la classe
    // ));
    // $resolver->setDefaults(array(
    //   'widget' => 'single_text',
    //   'input' => 'datetime',
    //   'format' => 'dd/MM/yyyy HH:mm',
      
      // 'attr' => [
      //     'class' => 'form-control input-inline datepicker',
      //     'data-provide' => 'datepicker',
      //     // 'data-date-format' => 'yyyy-mm-dd hh:mm:ss'
      // ]
    ));
  }

  public function getParent() // On utilise l'héritage de formulaire
  {
    return 'text';
  }

  public function getName()
  {
    return 'datepicker';
  }
}

?>