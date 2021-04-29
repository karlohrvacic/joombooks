<?php

namespace App\Service;

use App\Entity\Posudbe;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RezervacijaVerify extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function rezervacijaExpiration()
    {
        $now = new \DateTime();
        /**
         * @var $istekleRezervacije Posudbe
         */
        $istekleRezervacije = $this->em->createQueryBuilder()
            ->select('i')
            ->from('App:Posudbe', 'i')
            ->where('i.datumRokaVracanja > :date and i.status = 5')
            ->setParameter('date', $now)
            ->getQuery()
            ->getResult();
        dd($istekleRezervacije[0]->getDatumRokaVracanja(), $now);
    }

}
