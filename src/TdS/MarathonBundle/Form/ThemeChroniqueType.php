<?php
namespace TdS\MarathonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ThemeChroniqueType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('chronique','ckeditor')
                ->remove('titre')
                ->remove('saison')
                ->remove('dateDebut')
                ->remove('dateFin')
                ->remove('image')
                ->remove('description')
                ->remove('activate')
                ->remove('joggeur')
                ->remove('joggeurChronique');
    }

    public function getName(){
        return 'tds_marathon_theme_add_chronique';
    }

    public function getParent(){
        return new ThemeType();
    }
}


?>