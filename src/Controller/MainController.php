<?php

namespace App\Controller;

use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Service\RezervacijaVerify;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MainController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index(RezervacijaVerify $verify)
    {
        $verify->rezervacijaExpirationCheck();

        /** @var $user Korisnici */
        $user = $this->getUser();
        if($user instanceof Korisnici){
            return $this->redirectToRoute('korisnicki_izbornik');
        }
        /** @var $user Knjiznice */
        $user = $this->getUser();
        if($user instanceof Knjiznice){
            return $this->redirectToRoute('knjiznica_izbornik');
        }

        return $this->redirectToRoute('app_login');

    }

}