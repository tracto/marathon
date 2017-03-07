<?php
namespace AppBundle\Twig;


use Symfony\Component\Intl\Intl;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppExtension extends \Twig_Extension
{

    

    /** @var ContainerInterface */
    protected $container;    

    

    public function __construct(ContainerInterface $container)
    {
        
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    
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