<?php

namespace App\Controller;

use App\Controller\BarcodeController;
use ContainerExHJEvb\getBarcodeControllerService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @Route("/korisnik", name="korisnicki_profil")
     */
    public function korisnickiProfil()
    {
        #$isbnCode = makeISBN("0123456789");
       # dd($this->getUser());
        return $this->render('korisnickiProfil/korisnickiProfil.html.twig',[
            #'code' => $isbnCode
        ]);
    }
    /**
     * @Route("/knjiznica", name="knjiznica_profil")
     */
    public function knjiznicaProfil()
    {
        #dd($this->getUser());
        return $this->render('korisnickiProfil/knjiznicaProfil.html.twig',[

        ]);
    }
}