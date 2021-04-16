<?php

namespace App\Repository;

use App\Entity\Knjiznice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Knjiznice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Knjiznice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Knjiznice[]    findAll()
 * @method Knjiznice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KnjizniceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Knjiznice::class);
    }

    // /**
    //  * @return Knjiznice[] Returns an array of Knjiznice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Knjiznice
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
