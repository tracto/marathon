<?php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('scoreTotal', array($this, 'scoreTotalFilter')),
        );
    }

    public function scoreTotalFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $scoreTotal = number_format($number, $decimals, $decPoint, $thousandsSep);
        

        return $scoreTotal;
    }

    public function getName()
    {
        return 'app_extension';
    }
}