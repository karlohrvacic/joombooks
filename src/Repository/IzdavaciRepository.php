<?php

namespace App\Repository;

use App\Entity\Izdavaci;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Izdavaci|null find($id, $lockMode = null, $lockVersion = null)
 * @method Izdavaci|null findOneBy(array $criteria, array $orderBy = null)
 * @method Izdavaci[]    findAll()
 * @method Izdavaci[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IzdavaciRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Izdavaci::class);
    }

    // /**
    //  * @return Izdavaci[] Returns an array of Izdavaci objects
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
    public function findOneBySomeField($value): ?Izdavaci
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
