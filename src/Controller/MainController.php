<?php

namespace App\Controller;

use App\Entity\Autori;
use App\Entity\Drzave;
use App\Entity\Korisnici;
use App\Entity\Zanrovi;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {

        return $this->render('base.html.twig',[

        ]);
    }
}