<?php
namespace TdS\MarathonBundle\Entity;
use TdS\MarathonBundle\Entity\Saison;


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
			->select('a','i','jc','m')
			->from($this->_entityName,'a')
            ->leftJoin('a.image','i')
            ->leftJoin('a.joggeurChronique','jc') 
            ->leftJoin('a.musicTitles','m')
       		->orderBy('a.dateFin','DESC')
            ;
			


		return $queryBuilder
    		->getQuery()
    		->getResult();
    }

    public function findDerniersThemes($limit){
        $queryBuilder=$this->_em->createQueryBuilder('a')
        ->addselect('a','i','partial m.{id}','partial th.{id,numComments}')
        ->from($this->_entityName,'a')
                
        ->leftJoin('a.image','i')        
        ->leftJoin('a.musicTitles','m')
        ->leftJoin('a.thread','th')
        ->orderBy('a.id', 'DESC')
        ->groupBy('a.id')
        ->setFirstResult(0)
        ->setMaxResults($limit)     
        ;

        return $queryBuilder
            ->getQuery()
            ->getResult();

    }

    public function findOneThemeById($id){
      $queryBuilder=$this->_em->createQueryBuilder('a')
        ->addselect('a','i','m','mj','mji','j','ji','jc','jci','th')
        
        ->from($this->_entityName,'a')
        ->leftJoin('a.image','i')
        ->leftJoin('a.joggeur','j')
        ->leftJoin('j.image','ji')
        // ->leftJoin('a.scores','s')
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
