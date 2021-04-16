<?php

namespace App\Repository;

use App\Entity\Zanrovi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Zanrovi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Zanrovi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Zanrovi[]    findAll()
 * @method Zanrovi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZanroviRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zanrovi::class);
    }

    // /**
    //  * @return Zanrovi[] Returns an array of Zanrovi objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Zanrovi
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
