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

class ProfilKorisnikController extends AbstractController
{
    /**
     * @Route("/korisnik", name="korisnicki_izbornik")
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
     * @Route("/korisnik/posudjeno", name="posudjene_knjige_korisnika")
     */
    public function pregledPosudjenih(): Response
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

        return $this->render('korisnickiProfil/pregledPosudjenih.html.twig', [
            'posudbe' => $posudbe,
            'korisnik' => $korisnik,
            'code' => $code
        ]);
    }

    /**
     * @Route("/korisnik/gradja", name="pregled_knjiga")
     */
    public function pregledKnjiga(GradjaRepository $gradjaRepository, RezervacijaVerify $verify): Response
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
     * @Route("/korisnik/profil", name="korisnicki_profil")
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
     * @Route("/korisnik/obavijesti", name="korisnicke_obavijesti")
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
     * @Route("/korisnik/postavke", name="korisnicke_postavke")
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
     * @Route("/radno_vrijeme", name="radno_vrijeme")
     */
    public function radnovrijeme(): Response
    {
        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        return $this->render('korisnickiProfil/radnoVrijeme.html.twig', [
            'korisnik' => $korisnik
        ]);
    }

    #[Route('gradja/cancel/{id}', name: 'rezervacija_cancel', methods: ['GET'])]
    public function cancelation($id, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $user Korisnici
         */
        $user = $this->getUser();
        if ($user instanceof Korisnici) {
            if ($user->getBrojIskazniceKorisnika() == $rezervacija->getBrojIskazniceKorisnika()) {
                $rezervacija
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(8));
                $rezervacija->getGradja()
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(1));

                $entityManager->flush();

                $this->addFlash('success', 'Rezervacija uspješno otkazana!');

            }

            return $this->redirectToRoute('rezervirane_knjige_korisnika');
        }
        /**
         * @var $knjiznicar Knjiznice
         */
        $knjiznicar = $this->getUser();
        if ($knjiznicar) {
            if ($knjiznicar instanceof Knjiznice) {
                $rezervacija
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(8));
                $rezervacija->getGradja()
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(1));

                $entityManager->flush();

                $this->addFlash('success', 'Rezervacija uspješno otkazana!');

            }
            return $this->redirectToRoute('rezervacije_korisnika');
        }
        return $this->redirectToRoute('app_login');
    }

}
