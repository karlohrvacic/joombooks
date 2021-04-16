<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="korisnicki_profil")
     */
    public function korisnickiProfil()
    {
        return $this->render('korisnickiProfil/mainProfile.html.twig',[

        ]);
    }
    /**
     * @Route("/knjiznica", name="korisnicki_profil")
     */
    public function knjiznicaProfil()
    {
        return $this->render('korisnickiProfil/mainProfile.html.twig',[

        ]);
    }
}