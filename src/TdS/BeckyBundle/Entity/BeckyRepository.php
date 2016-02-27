<?php

namespace TdS\BeckyBundle\Entity;

use Doctrine\ORM\EntityRepository;
use TdS\BeckyBundle\Entity\Becky;

class BeckyRepository extends \Doctrine\ORM\EntityRepository {

	public function getRandom() {

		$count = $this->createQueryBuilder('u')
             ->select('COUNT(u)')
             ->getQuery()
             ->getSingleScalarResult();

        $queryBuilder=$this->createQueryBuilder('u')
			    ->setFirstResult(rand(0, $count - 1))
			    ->setMaxResults(1);

     

		return $queryBuilder
    		->getQuery()
    		->getSingleResult();
	}

}