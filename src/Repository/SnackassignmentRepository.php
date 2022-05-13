<?php

namespace App\Repository;

use App\Entity\Snackassignment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @extends ServiceEntityRepository<Snackassignment>
 *
 * @method Snackassignment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Snackassignment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Snackassignment[]    findAll()
 * @method Snackassignment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SnackassignmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Snackassignment::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Snackassignment $entity, bool $flush = true): void
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
    public function remove(Snackassignment $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Snackassignment[] Returns an array of Snackassignment objects
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
    public function findOneBySomeField($value): ?Snackassignment
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */   
    public function findAssignmentdetails()
    {
        $entityManager = $this->getEntityManager();
        $from = new \DateTime(date('Y-m-d'));
        $qb = $this->createQueryBuilder("e");
        $qb->andWhere('e.presentdate = :from')->setParameter('from', $from );
        return $qb->getQuery()->getResult();
        
        
       /* $query = $entityManager->createQuery(
            'SELECT snack_id
            FROM App\Entity\Snackassignment 
            WHERE presentdate="'.date('Y-m-d').'"');
            
            // returns an array of Product objects
            return $query->getResult();*/
    }
}