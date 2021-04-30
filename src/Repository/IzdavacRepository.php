<?php

namespace App\Repository;

use App\Entity\Izdavac;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Izdavac|null find($id, $lockMode = null, $lockVersion = null)
 * @method Izdavac|null findOneBy(array $criteria, array $orderBy = null)
 * @method Izdavac[]    findAll()
 * @method Izdavac[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IzdavacRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Izdavac::class);
    }

    // /**
    //  * @return Izdavac[] Returns an array of Izdavac objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Izdavac
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
