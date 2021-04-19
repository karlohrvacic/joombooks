<?php

namespace App\Controller;

use App\Controller\BarcodeController;
use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Repository\GradjaRepository;
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

    /**
     * @Route("/korisnik/posudjeno", name="posudjene_knjige_korisnika")
     */
    public function pregledPosudjenih()
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();
        dd($korisnik->getPosudbe());

        return $this->render('korisnickiProfil/pregledPosudjenih.html.twig',[
            'posudbe' => $korisnik->getPosudbe()
        ]);
    }

    /**
     * @Route("/korisnik/gradja", name="pregled_knjiga")
     */
    public function pregledKnjiga(GradjaRepository $gradjaRepository)
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();


        return $this->render('gradja/index.html.twig', [
            'gradjas' => $gradjaRepository->findBy([
                'knjiznicaVlasnik' => $korisnik->getKnjiznice()
            ]),
        ]);
    }
}