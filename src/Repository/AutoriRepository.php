<?php

namespace App\Repository;

use App\Entity\Autori;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Autori|null find($id, $lockMode = null, $lockVersion = null)
 * @method Autori|null findOneBy(array $criteria, array $orderBy = null)
 * @method Autori[]    findAll()
 * @method Autori[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutoriRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Autori::class);
    }

    // /**
    //  * @return Autori[] Returns an array of Autori objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Autori
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
