<?php
namespace TdS\MarathonBundle\Entity;

use TdS\MarathonBundle\Entity\Saison;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ThemeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ThemeRepository extends \Doctrine\ORM\EntityRepository{
	public function findAll(){
        return $this->findBy(array(), array('dateFin' => 'DESC'));
    }

    public function findAllThemes(){
        	$queryBuilder=$this->_em->createQueryBuilder()
			->select('a','i','jc','m','mj','mji')
			->from($this->_entityName,'a')
            ->leftJoin('a.image','i')
            ->leftJoin('a.joggeurChronique','jc') 
            ->leftJoin('a.musicTitles','m')
            ->leftJoin('m.joggeur','mj')
            ->leftJoin('mj.image','mji')
       		->orderBy('a.dateFin','DESC')
            ;
			

		return $queryBuilder
    		->getQuery()
    		->getResult();
    }

    public function findDerniersThemes($limit){
        $queryBuilder=$this->_em->createQueryBuilder()
        ->select('a') 
        ->where('a.statut != :statut')
        ->setParameter('statut', 0)      
        ->orderBy('a.id', 'DESC')
        ->setFirstResult(0)
        ->setMaxResults($limit) 
        ->from($this->_entityName,'a')       
        ;

        $paginator = new Paginator($queryBuilder, $fetchJoin = true);
        return $paginator;

    }

    public function findOneThemeById($id){
      $queryBuilder=$this->_em->createQueryBuilder('a')
        ->addselect('a','i','m','mj','mji','j','ju','ji','jc','jci','th')        
        ->from($this->_entityName,'a')
        ->leftJoin('a.image','i')
        ->leftJoin('a.joggeur','j')
        ->leftJoin('j.user','ju')
        ->leftJoin('j.image','ji')
        ->leftJoin('a.joggeurChronique','jc')
        ->leftJoin('jc.image','jci')         
        ->leftJoin('a.musicTitles','m')
        ->leftJoin('m.joggeur','mj')
        ->leftJoin('mj.image','mji')
        ->leftJoin('a.thread','th') 
         ->where('a.id = :id')
        ->setParameter('id', $id)      
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }


    public function findOneThemeByStatut($statut){
      $queryBuilder=$this->_em->createQueryBuilder('a')
        ->addselect('a','i','j','ji','jc','jci','m','mj','th')
        ->andwhere('a.statut = :statut')

        ->setParameter('statut', $statut)
        ->from($this->_entityName,'a')
        ->leftJoin('a.image','i')
        ->leftJoin('a.joggeur','j')
        ->leftJoin('j.image','ji')
        ->leftJoin('a.joggeurChronique','jc')
        ->leftJoin('jc.image','jci')         
        ->leftJoin('a.musicTitles','m')
        ->leftJoin('m.joggeur','mj')
        ->leftJoin('a.thread','th')        
        ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function find3Themes(){
        $statuts=array('0','1','2');

        $queryBuilder=$this->_em->createQueryBuilder('a')
          ->addselect('a','i')
          ->andWhere('a.statut IN (:statuts)')
          ->setParameter('statuts', $statuts) 
          ->from($this->_entityName,'a')
          ->leftJoin('a.image','i')                  
          ;

          return $queryBuilder
            ->getQuery()
            ->getResult();
    }



    public function findThemeOrderByDateFinOnlyId(){
      $queryBuilder=$this->_em->createQueryBuilder('a')
        ->addselect('partial a.{id, dateFin, statut}')
        
        ->from($this->_entityName,'a') 
        ->orderBy('a.dateFin', 'DESC')     
        ;

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }


    





    
}
