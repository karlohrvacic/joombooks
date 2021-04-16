<?php

namespace App\Repository;

use App\Entity\Drzave;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Drzave|null find($id, $lockMode = null, $lockVersion = null)
 * @method Drzave|null findOneBy(array $criteria, array $orderBy = null)
 * @method Drzave[]    findAll()
 * @method Drzave[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrzaveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drzave::class);
    }

    // /**
    //  * @return Drzave[] Returns an array of Drzave objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Drzave
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
