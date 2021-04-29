<?php

namespace App\Service;

use App\Entity\Posudbe;
use App\Repository\PosudbeRepository;
use Doctrine\ORM\EntityManagerInterface;

class RezervacijaVerify
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function rezervacijaExpiration()
    {
        $istekleRezervacije = $this->entityManager->getRepository(PosudbeRepository::class)->findBy(
            ['status_id' => 5]
        );
        dd($istekleRezervacije);
    }

}
