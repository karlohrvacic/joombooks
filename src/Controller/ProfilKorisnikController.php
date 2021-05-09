<?php

namespace App\Controller;

use App\Entity\Korisnici;
use App\Entity\Posudbe;
use App\Repository\AutoriRepository;
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
    public function korisnickiIzbornik(RezervacijaVerify $verify): Response
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
            'korisnici' => $korisnik,
            'status'  => array(3, 9)
        ]);

        $code = new BarcodeController();

        return $this->render('korisnickiProfil/pregledPosudjenih.html.twig', [
            'posudbe' => $posudbe,
            'korisnik' => $korisnik,
            'code' => $code
        ]);
    }


    #[Route('/gradja', name: 'pregled_knjiga', methods: ['GET', 'POST'])]
    public function pregledKnjiga(Request $request, GradjaRepository $gradjaRepository, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        $query = $request->query->get('query');

        if($query){
            $gradja = $gradjaRepository->findByAutorAndNazivPoOibuKnjiznice($query, $korisnik->getKnjiznice());
        } else{
            $gradja = $gradjaRepository->findBy([
                'knjiznicaVlasnik' => $korisnik->getKnjiznice(),
            ]);
        }

        return $this->render('korisnickiProfil/pregledGradje.html.twig', [
            'gradjas' => $gradja,
            'korisnik' => $korisnik,
            'last_query' => $query
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
            'korisnici' => $korisnik,
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

    /**
     * @Route("/postavke/spremi", name="spremi_postavke")
     */
    public function spremiPostavke(Request $request): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $entity = $entityManager->getRepository(Korisnici::class)
            ->find($this->getUser()->getId());

        $entity->setPostavke($_POST);
        $entityManager->flush();
        $this->addFlash('success', 'Postavke uspješno spremljene!');

        return $this->redirectToRoute('korisnicke_postavke');
    }

}
