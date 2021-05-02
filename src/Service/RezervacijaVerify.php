<?php

namespace App\Service;

use App\Entity\Posudbe;
use App\Entity\Statusi;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class RezervacijaVerify
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function rezervacijaExpirationCheck()
    {
        $now = new DateTime();
        /**
         * @var $istekleRezervacije Posudbe
         */
        $istekleRezervacije = $this->em->createQueryBuilder()
            ->select('i')
            ->from('App:Posudbe', 'i')
            ->where('i.datumRokaVracanja < :date and i.status = 5')
            ->setParameter('date', $now)
            ->getQuery()
            ->getResult();

        foreach ($istekleRezervacije as $rezervacija){
            $rezervacija
                ->setStatus($this->em->getRepository(Statusi::class)
                    ->find(7));
            $rezervacija->getGradja()
                ->setStatus($this->em->getRepository(Statusi::class)
                    ->find(1));
        }
        $this->em->flush();
    }
}
