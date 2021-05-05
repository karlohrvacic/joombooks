<?php

namespace App\Controller;

use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Service\RezervacijaVerify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function index(RezervacijaVerify $verify): RedirectResponse
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