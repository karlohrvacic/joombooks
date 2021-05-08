<?php

namespace App\Repository;

use App\Entity\Gradja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gradja|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gradja|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gradja[]    findAll()
 * @method Gradja[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GradjaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gradja::class);
    }

     /**
      * @return Gradja[] Returns an array of Gradja objects
      */

    public function findByAutorAndNazivPoOibuKnjiznice($value, $knjiznica)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.knjiznicaVlasnik = :oib AND (g.naslov LIKE :val )')
            ->setParameter('oib', $knjiznica->getId())
            ->setParameter('val', "%".$value."%")
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Gradja
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
