<?php


namespace TdS\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Security\Core\User\UserInterface;


class User extends BaseUser{
	/**
	  * @ORM\Column(name="id", type="integer")
	  * @ORM\Id
	  * @ORM\GeneratedValue(strategy="AUTO")
	  */
	 protected $id;


	 /**
       * @ORM\OneToOne(targetEntity="TdS\MarathonBundle\Entity\Joggeur", cascade={"persist","merge"}, inversedBy="user")
        * @ORM\JoinColumn(nullable=false)
       */
     private $joggeur;

     /**
     * Set joggeur
     *
     * @param \TdS\MarathonBundle\Entity\Joggeur $joggeur
     * @return Joggeur
     */
    public function setJoggeur(\TdS\MarathonBundle\Entity\Joggeur $joggeur = null)
    {
        $this->joggeur = $joggeur;
        return $this;
    }

    /**
     * Get joggeur
     *
     * @return \TdS\MarathonBundle\Entity\Joggeur
     */
    public function getJoggeur()
    {
        return $this->joggeur;
    }

 }
