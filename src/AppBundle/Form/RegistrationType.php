<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label' => 'email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', null, array('label' => 'utilisateur', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'mot de passe'),
                'second_options' => array('label' => 'confirmation du mot de passe'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
        ;
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}
?>