<?php

namespace App\Repository;

use App\Entity\Snacks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Snacks>
 *
 * @method Snacks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Snacks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Snacks[]    findAll()
 * @method Snacks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SnacksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Snacks::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Snacks $entity, bool $flush = true): void
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
    public function remove(Snacks $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Snacks[] Returns an array of Snacks objects
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
    public function findOneBySomeField($value): ?Snacks
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findSnackname (int $snackid):array
    {
        $entityManager = $this->getEntityManager();
       /*  $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            WHERE p.price > :pce
            ORDER BY p.price ASC'
            )->setParameter('pce', $price); */
        
        $query = $entityManager-> createQuery(
            'select s.snackname 
             FROM App\Entity\Snacks s 
             where s.id=:snk'
             )->setParameter('snk',$snackid);
        
        return $query->getResult();
    }
    
}
