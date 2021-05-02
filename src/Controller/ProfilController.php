<?php

namespace App\Controller;

use App\Entity\Korisnici;
use App\Entity\Posudbe;
use App\Repository\GradjaRepository;
use App\Service\RezervacijaVerify;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    /**
     * @Route("/korisnik", name="korisnicki_izbornik")
     */
    public function korisnickiIzbornik(RezervacijaVerify $verify)
    {
        $verify->rezervacijaExpirationCheck();

        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        return $this->render('korisnickiProfil/korisnickaPocetna.html.twig',[
            'korisnik' => $korisnik,
        ]);
    }
    /**
     * @Route("/knjiznica", name="knjiznica_izbornik")
     */
    public function knjiznicaProfil()
    {
        #dd($this->getUser());
        return $this->render('knjiznicniProfil/knjiznicaPocetna.html.twig',[

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

        $code = new BarcodeController();

        return $this->render('korisnickiProfil/pregledPosudjenih.html.twig',[
            'posudbe' => $posudbe,
            'korisnik' => $korisnik,
            'code' => $code
        ]);
    }

    /**
     * @Route("/korisnik/gradja", name="pregled_knjiga")
     */
    public function pregledKnjiga(GradjaRepository $gradjaRepository, RezervacijaVerify $verify)
    {
        $verify->rezervacijaExpirationCheck();
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        return $this->render('gradja/index.html.twig', [
            'gradjas' => $gradjaRepository->findBy([
                'knjiznicaVlasnik' => $korisnik->getKnjiznice(),
            ]),
            'korisnik' => $korisnik
        ]);
    }

    /**
     * @Route("/korisnik/rezervirano", name="rezervirane_knjige_korisnika")
     */
    public function pregledRezerviranih(RezervacijaVerify $verify)
    {
        $verify->rezervacijaExpirationCheck();
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

    /**
     * @Route("/korisnik/profil", name="korisnicki_profil")
     */
    public function korisnickiProfil()
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        $code = new BarcodeController();

        return $this->render('korisnickiProfil/profil.html.twig',[
            'korisnik' => $korisnik,
            'code' => $code
        ]);
    }

    /**
     * @Route("/korisnik/obavijesti", name="korisnicke_obavijesti")
     */
    public function korisnickeObavijesti()
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        return $this->render('korisnickiProfil/obavijesti.html.twig',[
            'korisnik' => $korisnik
        ]);
    }

    /**
     * @Route("/korisnik/postavke", name="korisnicke_postavke")
     */
    public function korisnickePostavke()
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        return $this->render('korisnickiProfil/postavke.html.twig',[
            'korisnik' => $korisnik
        ]);
    }

    /**
     * @Route("/radno_vrijeme", name="radno_vrijeme")
     */
    public function radnovrijeme()
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        return $this->render('korisnickiProfil/postavke.html.twig',[
            'korisnik' => $korisnik
        ]);
    }
}