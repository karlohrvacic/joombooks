<?php

namespace App\Controller;

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

        return $this->redirectToRoute('app_login');

    }

}