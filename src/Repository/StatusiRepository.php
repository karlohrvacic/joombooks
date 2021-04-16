<?php

namespace App\Repository;

use App\Entity\Statusi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Statusi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statusi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statusi[]    findAll()
 * @method Statusi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statusi::class);
    }

    // /**
    //  * @return Statusi[] Returns an array of Statusi objects
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
    public function findOneBySomeField($value): ?Statusi
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
