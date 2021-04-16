<?php

namespace App\Repository;

use App\Entity\Posudbe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Posudbe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posudbe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posudbe[]    findAll()
 * @method Posudbe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PosudbeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posudbe::class);
    }

    // /**
    //  * @return Posudbe[] Returns an array of Posudbe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Posudbe
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
