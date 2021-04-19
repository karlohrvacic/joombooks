<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class MainController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {

        return $this->redirectToRoute('app_login');

    }

}