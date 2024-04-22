<?php

namespace App\Repository;

use App\Entity\Users;
use App\Entity\Roles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Users $entity, bool $flush = true): void
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
    public function remove(Users $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    
    
    // /**
    //  * @return Users[] Returns an array of Users objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    /* public function makeAdmin(int $userid):array
    {
        $entityManager = $this->getEntityManager();
        
        $query = $entityManager->createQuery(update('App\Entity\Users', 'u')
            ->set('u.role', ':roleid')
            ->where('u.id = :userid')
            ->setParameter('roleid', 1)
            ->setParameter('userid', $userid)
            ->getQuery();
        
            $result = $query->execute();
            
            return $result;            
    }
    public function removeAdmin(int $userid):array
    {
        $entityManager = $this->getEntityManager();
        
        $query = $entityManager->createQuery(update('App\Entity\Users', 'u')
            ->set('u.role', ':roleid')
            ->where('u.id = :userid')
            ->setParameter('roleid', 2)
            ->setParameter('userid', $userid)
            ->getQuery();
            
            $result = $query->execute();
            
            return $result;
    } */
    
    public function fetchUserdetails ($email, $pwd):array
    {
        $entityManager = $this->getEntityManager();
        
           /*  'select s.snackname
             FROM App\Entity\Snacks s
             where s.id=:snk'
            )->setParameter('snk',$snackid); */
        
        $query = $entityManager->createQuery(
            'select s.id,identity(s.role) as rl,s.employeename
             FROM App\Entity\Users s
             where s.username=:uname' // AND s.password=:pwd'
            )->setParameter('uname',$email); //, 'pwd' => $pwd));
            
           // dd($query);
            
            return $query->getResult();
    }
    public function getAllUser()
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'select u.id,u.employeename FROM App\Entity\Users u'
            );
        return $query->getResult();
        
    }
    public function getAlladminuser()
    {
        $entityManager = $this->getEntityManager();
        
        $query = $entityManager->createQuery(
            'select u.id FROM App\Entity\Users u where u.role=:adminroleid'
            )->setParameter('adminroleid',1);
        
            return $query->getResult();
    }
    
}
