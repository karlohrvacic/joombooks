<?php

namespace App\Controller;

use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Entity\Posudbe;
use App\Entity\Statusi;
use App\Repository\GradjaRepository;
use App\Service\RezervacijaVerify;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/korisnik')]
class ProfilKorisnikController extends AbstractController
{
    /**
     * @Route("/", name="korisnicki_izbornik")
     */
    public function korisnickiIzbornik(Request $request, RezervacijaVerify $verify): Response
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
     * @Route("/posudjeno", name="posudjene_knjige_korisnika")
     */
    public function pregledPosudjenih(): Response
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        $posudbe = $this->getDoctrine()->getManager()->getRepository(Posudbe::class)->findBy([
            'brojIskazniceKorisnika' => $korisnik->getBrojIskazniceKorisnika(),
            'status'  => array(3, 9)
        ]);

        $code = new BarcodeController();

        return $this->render('korisnickiProfil/pregledPosudjenih.html.twig', [
            'posudbe' => $posudbe,
            'korisnik' => $korisnik,
            'code' => $code
        ]);
    }

    /**
     * @Route("/gradja", name="pregled_knjiga")
     */
    public function pregledKnjiga(GradjaRepository $gradjaRepository, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        return $this->render('korisnickiProfil/pregledGradje.html.twig', [
            'gradjas' => $gradjaRepository->findBy([
                'knjiznicaVlasnik' => $korisnik->getKnjiznice(),
            ]),
            'korisnik' => $korisnik
        ]);
    }

    /**
     * @Route("/rezervirano", name="rezervirane_knjige_korisnika")
     */
    public function pregledRezerviranih(RezervacijaVerify $verify): Response
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

        return $this->render('korisnickiProfil/pregledRezerviranih.html.twig', [
            'posudbe' => $posudbe,
            'korisnik' => $korisnik
        ]);
    }

    /**
     * @Route("/profil", name="korisnicki_profil")
     */
    public function korisnickiProfil(): Response
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        $code = new BarcodeController();

        return $this->render('korisnickiProfil/profil.html.twig', [
            'korisnik' => $korisnik,
            'code' => $code
        ]);
    }

    /**
     * @Route("/obavijesti", name="korisnicke_obavijesti")
     */
    public function korisnickeObavijesti(): Response
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        return $this->render('korisnickiProfil/obavijesti.html.twig', [
            'korisnik' => $korisnik
        ]);
    }

    /**
     * @Route("/postavke", name="korisnicke_postavke")
     */
    public function korisnickePostavke(): Response
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        return $this->render('korisnickiProfil/postavke.html.twig', [
            'korisnik' => $korisnik
        ]);
    }



}
