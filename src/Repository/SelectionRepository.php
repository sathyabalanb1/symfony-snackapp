<?php

namespace App\Repository;

use App\Entity\Selection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




/**
 * @extends ServiceEntityRepository<Selection>
 *
 * @method Selection|null find($id, $lockMode = null, $lockVersion = null)
 * @method Selection|null findOneBy(array $criteria, array $orderBy = null)
 * @method Selection[]    findAll()
 * @method Selection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SelectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Selection::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Selection $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Selection $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Selection[] Returns an array of Selection objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Selection
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */  
    
    public function fetchSelectiondetails ($userid, $currentdate)
    {
        $entityManager = $this->getEntityManager();
        $qb = $this->createQueryBuilder('s');
        $qb->where('s.user = :uid');
        $qb->andWhere('s.createdtime LIKE :date');
        $qb->setParameter('uid',$userid);
        $qb->setParameter('date',"$currentdate%");
        return $qb->getQuery()->getResult();
      /*  $query = $entityManager->createQuery(
            'select s.isselected
             FROM App\Entity\Selection s
             where s.userid=:uid AND YEAR(s.createdtime)=:cdate'
            )->setParameter('uid',$userid
            )->setParameter('cdate',$currentdate); 
        
          //  echo $query;
           // exit;*/
           // return $query->getResult();     
    }
    /*
    public function findSnackname (int $snackid):array
    {
        $entityManager = $this->getEntityManager();
        /*  $query = $entityManager->createQuery(
         'SELECT p
         FROM App\Entity\Product p
         WHERE p.price > :pce
         ORDER BY p.price ASC'
         )->setParameter('pce', $price); */
    /*    
        $query = $entityManager-> createQuery(
            'select s.snackname
             FROM App\Entity\Snacks s
             where s.id=:snk'
            )->setParameter('snk',$snackid);
            
            return $query->getResult();
    }
    */
    
    public function updateNochoice ($userid,$assignmentid)
    {
       // $assignmentid=$this->get('session')->get('assignmentid');
       // $userid=$this->get('session')->get('userid');
       
        $entityManager = $this->getEntityManager();
        
        
       // $queryBuilder = $this->em->createQueryBuilder();
       $queryBuilder = $entityManager->createQueryBuilder();
        $query = $queryBuilder->update('App\Entity\Selection', 's')
        ->set('s.isselected', ':value')
        ->where('s.user = :userid')
        ->andwhere('s.assignment = :assignmentid')
        ->setParameter('userid', $userid)
        ->setParameter('assignmentid', $assignmentid)
        ->setParameter('value', '0')
        ->getQuery();
        $result = $query->execute();
        return;    
        
    }
    public function updateYeschoice ($userid,$assignmentid)
    {
        // $assignmentid=$this->get('session')->get('assignmentid');
        // $userid=$this->get('session')->get('userid');
        
        $entityManager = $this->getEntityManager();
        
        
        // $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder = $entityManager->createQueryBuilder();
        $query = $queryBuilder->update('App\Entity\Selection', 's')
        ->set('s.isselected', ':value')
        ->where('s.user = :userid')
        ->andwhere('s.assignment = :assignmentid')
        ->setParameter('userid', $userid)
        ->setParameter('assignmentid', $assignmentid)
        ->setParameter('value', '1')
        ->getQuery();
        $result = $query->execute();
        return;
        
    }
     public function fetchYeschoiceusers ($startdate)
    {
            $entityManager = $this->getEntityManager();
        
            /* $query = $entityManager->createQuery(
                'select u.id,u.employeename,s.createdtime
                 FROM App\Entity\Selection s, App\Entity\Users u
                 where s.isselected=:snk AND s.user = u.id AND s.createdtime LIKE :date'
                )->setParameter('snk',1)->setParameter('date',"$startdate%"); */
            
                $query = $entityManager->createQuery(
                    'select u.id,u.employeename,s.createdtime, s.isselected
                 FROM App\Entity\Selection s, App\Entity\Users u
                 where s.user = u.id AND  s.createdtime LIKE :date'
                    )->setParameter('date',"$startdate%");
            return $query->getResult();        
             
    } 
    public function fetchNoresponseusers ($startdate)
    {
        $entityManager = $this->getEntityManager();
        
        $query = $entityManager->createQuery(
                 'select u.id,u.employeename FROM App\Entity\Users  u
                 where u.id NOT IN(select us.id
                 FROM App\Entity\Selection s, App\Entity\Users us
                 where s.user = us.id AND  s.createdtime LIKE :date)')->setParameter('date',"$startdate%");
                 return $query->getResult();
                
                 //u.employeename,s.createdtime, s.isselected
    }
    public function findSelectedusers ($startdate, $enddate)
    {
        
        $entityManager = $this->getEntityManager();
        
        /* $query = $entityManager->createQuery(
         'select u.id,u.employeename,s.createdtime
         FROM App\Entity\Selection s, App\Entity\Users u
         where s.isselected=:snk AND s.user = u.id AND s.createdtime LIKE :date'
         )->setParameter('snk',1)->setParameter('date',"$startdate%"); */
        
        $query = $entityManager->createQuery(
            'select u.id,u.employeename,s.createdtime, s.isselected
                 FROM App\Entity\Selection s, App\Entity\Users u
                 where s.user = u.id AND s.createdtime BETWEEN :sdate AND :edate'
            )->setParameter('sdate',"$startdate"
                )->setParameter('edate',"$enddate")
        ;
            return $query->getResult();
            
    } 
    public function findNoresponseusers ($startdate, $enddate)
    {
        $entityManager = $this->getEntityManager();
        
         
          /* $query = $entityManager->createQuery(
            'select u.id,u.employeename,ss.createdtime FROM App\Entity\Selection ss,App\Entity\Users u
                 where u.id NOT IN(select us.id
                 FROM App\Entity\Selection s, App\Entity\Users us
                 where s.user = us.id AND  s.createdtime BETWEEN :sdate AND :edate)' 
            )->setParameter('sdate',$startdate)
            ->setParameter('edate',$enddate); 
           */
            $selectionquery = $entityManager->createQuery(
                'select s.user,s.createdtime FROM App\Entity\Selection  s
                 where s.createdtime BETWEEN :sdate AND :edate'
                )->setParameter('sdate',"$startdate"
                )->setParmeter('edate',"$enddate");
        
        
        
        return $query->getResult();
        
        //u.employeename,s.createdtime, s.isselected
    }
    public function getTodayselection ($date1,$date2)
    {
        $entityManager = $this->getEntityManager();
        
        $query = $entityManager->createQuery(
            'select s.isselected
                 FROM App\Entity\Selection s
                 where s.createdtime BETWEEN :currentdatestart AND :currentdateend'
            )->setParameter('currentdatestart',$date1
                )->setParameter('currentdateend',$date2);
        
            return $query->getResult();
    }
   
}
