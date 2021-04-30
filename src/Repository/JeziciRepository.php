<?php

namespace App\Repository;

use App\Entity\Jezici;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Jezici|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jezici|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jezici[]    findAll()
 * @method Jezici[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JeziciRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jezici::class);
    }

    // /**
    //  * @return Jezici[] Returns an array of Jezici objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Jezici
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
