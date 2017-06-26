<?php
namespace TdS\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ParticipateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email')
                ->add('pseudo', 'text')
                ->add('envoyer','submit');
    }

    public function getName()
    {
        return 'Participate';
    }
}
?>