<?php

namespace App\Repository;

use App\Entity\Clanstva;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Clanstva|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clanstva|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clanstva[]    findAll()
 * @method Clanstva[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClanstvaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clanstva::class);
    }

    // /**
    //  * @return Clanstva[] Returns an array of Clanstva objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Clanstva
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
