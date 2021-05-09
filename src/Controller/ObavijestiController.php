<?php

namespace App\Controller;

use App\Entity\Korisnici;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ObavijestiController extends AbstractController
{

    /**
     * @Route("/korisnik/obavijesti", name="korisnicke_obavijesti")
     */
    public function korisnickeObavijesti(): Response
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        $notifications = array();

        $array = $korisnik->getNotifications();
        if (!empty($array)){
            array_push($notifications, $array);
        }
        $array = $this->notifyCheck($korisnik);
        if (!empty($array)){
            array_push($notifications, $array);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->getRepository(Korisnici::class)->find($korisnik->getId());
        $entity->setNotifications(null);
        $entityManager->flush();
        return $this->render('korisnickiProfil/obavijesti.html.twig', [
            'korisnik' => $korisnik,
            'notification_layer' => $notifications
        ]);
    }

    public function notifyCheck(Korisnici $korisnik)
    {
        $array = [];
        foreach ($korisnik->getPosudbe() as $posudba){
            $zakasnina = $posudba->zakasnina();
            if($zakasnina > 0 && $posudba->getStatus()->getId() == 3){
                $naslov = $posudba->getGradja()->getNaslov();
                $array[] = "Knjiga $naslov nije vraćena na vrijeme!
                Zakasnina trenutno iznosi $zakasnina kn";

            }
            if($posudba->brojDanaIsteka() == 1 && $posudba->getStatus()->getId() == 3){
                $naslov = $posudba->getGradja()->getNaslov();
                $array[] = "Rok za povratak $naslov istječe sutra!
                Zatražite produživanje posudbe na vrijeme!";
            }
            if($posudba->brojDanaIsteka() == 1 && $posudba->getStatus()->getId() == 5){
                $naslov = $posudba->getGradja()->getNaslov();
                $array[] =  "Rok za preuzimanje $naslov istječe sutra!";
            }
        }

        if (!empty($array)){
            return $array;
        }
    }



}
