<?php

namespace TdS\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class UserRepository extends EntityRepository implements UserProviderInterface
{

	public function findAllUsersWithJoggeurs(){
    	$queryBuilder = $this->createQueryBuilder('c')
        	->addSelect('c','i')
        	->leftJoin('c.joggeur', 'j')
        	->orderBy('c.id', 'DESC');

        return $queryBuilder
     		->getQuery()
     		->getResult();
    }
}