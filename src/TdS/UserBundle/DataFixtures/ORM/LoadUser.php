<?php
namespace TdS\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TdS\UserBundle\Entity\User;

class LoadUser implements FixtureInterface{
	public function load(ObjectManager $manager){
		$listNames= array ('Anne-Claire','Aldo');

		foreach($listNames as $name){
			$user=new User;
			$user->setUsername($name);
			$user->setPassword($name);

			$user->setSalt('');
			$user->setRoles(array('ROLE_ARTISTE'));
			$manager->persist($user);
		}

		$manager->flush();
	}
}


?>