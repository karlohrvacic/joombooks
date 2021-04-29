<?php

namespace App\Controller;

use App\Controller\BarcodeController;
use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Entity\Posudbe;
use App\Repository\GradjaRepository;
use App\Repository\KorisniciRepository;
use App\Service\RezervacijaVerify;
use ContainerExHJEvb\getBarcodeControllerService;
use ContainerKqqSjSH\getRezervacijaVerifyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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

        $posudbe = $this->getDoctrine()->getManager()->getRepository(Posudbe::class)->findBy([
            'brojIskazniceKorisnika' => $korisnik->getBrojIskazniceKorisnika(),
            'status' => 3
        ]);

        return $this->render('korisnickiProfil/pregledPosudjenih.html.twig',[
            'posudbe' => $posudbe
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

    /**
     * @Route("/korisnik/rezervirano", name="rezervirane_knjige_korisnika")
     */
    public function pregledRezerviranih()
    {

        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        $posudbe = $this->getDoctrine()->getManager()->getRepository(Posudbe::class)->findBy([
            'brojIskazniceKorisnika' => $korisnik->getBrojIskazniceKorisnika(),
            'status' => 5
        ]);

        return $this->render('korisnickiProfil/pregledRezerviranih.html.twig',[
            'posudbe' => $posudbe
        ]);
    }
}