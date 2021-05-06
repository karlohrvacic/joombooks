<?php

namespace App\Controller;

use App\Entity\Gradja;
use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Entity\Posudbe;
use App\Entity\Statusi;
use App\Repository\GradjaRepository;
use App\Service\RezervacijaVerify;
use DateInterval;
use DateTime;
use Exception;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/korisnik", name="korisnicki_izbornik")
     */
    public function korisnickiIzbornik(RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();

        /**
         * @var $korisnik Korisnici
         */
        $korisnik = $this->getUser();

        return $this->render('korisnickiProfil/korisnickaPocetna.html.twig', [
            'korisnik' => $korisnik,
        ]);
    }

    /**
     * @Route("/knjiznica", name="knjiznica_izbornik")
     */
    public function knjiznicaProfil(): Response
    {
        #dd($this->getUser());
        return $this->render('knjiznicniProfil/knjiznicaPocetna.html.twig', [

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
            'posudbe' => $posudbe
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

    /**
     * @Route("/knjiznica/rezervirano", name="rezervacije_korisnika")
     */
    public function pregledRezervacija(RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        /**
         * @var $knjiznica Knjiznice
         */
        $knjiznica = $this->getUser();

        $posudbe = $this->getDoctrine()->getManager()->getRepository(Posudbe::class)->findBy([
            'knjiznica' => $knjiznica,
            'status' => 5
        ]);

        $code = new BarcodeController();


        return $this->render('knjiznicniProfil/rezervirane.html.twig', [
            'posudbes' => $posudbe,
            'code' => $code
        ]);
    }

    /**
     * @Route("/knjiznica/posudjeno", name="posudbe_korisnika")
     */
    public function pregledPosudbi(RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        /**
         * @var $knjiznica Knjiznice
         */
        $knjiznica = $this->getUser();

        $posudbe = $this->getDoctrine()->getManager()->getRepository(Posudbe::class)->findBy([
            'knjiznica' => $knjiznica,
            'status' => 3
        ]);

        $code = new BarcodeController();

        return $this->render('knjiznicniProfil/posudjene.html.twig', [
            'posudbes' => $posudbe,
            'code' => $code
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

    /**
     * @throws Exception
     */
    #[Route('knjiznica/gradja/posudi/{id}', name: 'posudi_rezerviranu_gradju', methods: ['GET'])]
    public function posudba($id, RezervacijaVerify $verify): RedirectResponse
    {
        $verify->rezervacijaExpirationCheck();
        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $knjiznicar Knjiznice
         */
        $knjiznicar = $this->getUser();

        if ($knjiznicar) {
            if ($knjiznicar === $rezervacija->getKnjiznica()) {
                $rezervacija
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(3));
                $rezervacija->getGradja()
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(3));

                $rezervacija->setDatumPosudbe((new DateTime())->add(new DateInterval('P0D')));
                $daniPosudbe = $knjiznicar->getDaniPosudbe();
                $duration = "P" . $daniPosudbe . "D";

                $rezervacija->setDatumRokaVracanja((new DateTime())->add(new DateInterval($duration)));

                $entityManager->flush();

                $this->addFlash('success', 'Građa uspješno posuđena!');

            }
            return $this->redirectToRoute('posudbe_korisnika');
        }
        return $this->redirectToRoute('app_login');
    }

    /**
     * @throws Exception
     */
    #[Route('knjiznica/gradja/posudi/{idGradja}/{idKorisnika}', name: 'posudi_gradju', methods: ['GET'])]
    public function posudbaBezRezervacije(Request $request, $idGradja, $idKorisnika, RezervacijaVerify $verify): RedirectResponse
    {
        $verify->rezervacijaExpirationCheck();
        $entityManager = $this->getDoctrine()->getManager();
        $korisnik = $entityManager->getRepository(Korisnici::class)->find($idKorisnika);

        /**
         * @var $knjiznicar Knjiznice
         */
        $knjiznicar = $this->getUser();

        if ($korisnik->getBrojTrenutnoPosudenih() < $knjiznicar->getMaxPosudjenih()) {
            $posudbe = new Posudbe();
            $entityManager = $this->getDoctrine()->getManager();

            $gradja = $entityManager->getRepository(Gradja::class)->find($idGradja);

            $posudbe->setGradja($gradja);
            $posudbe->setKorisnici($korisnik);
            $posudbe->setStatus($entityManager->getRepository(Statusi::class)->find(3));

            $posudbe->setDatumPosudbe((new DateTime())->add(new DateInterval('P0D')));
            $posudbe->setKnjiznica($knjiznicar);
            $daniPosudbe = $knjiznicar->getDaniPosudbe();
            $duration = "P".$daniPosudbe."D";

            $posudbe->setDatumRokaVracanja((new DateTime())->add(new DateInterval($duration)));
            $posudbe->setBrojIskazniceKorisnika($korisnik->getBrojIskazniceKorisnika());
            $gradja->setStatus($entityManager->getRepository(Statusi::class)->find(3));
            $korisnik->addPosudbe($posudbe);

            $entityManager->persist($posudbe);
            $entityManager->persist($gradja);
            $entityManager->persist($korisnik);
            $entityManager->flush();


            $this->addFlash('success', 'Građa uspješno posuđena!');

        } else {
            $borrowed = $korisnik->getBrojTrenutnoPosudenih();
            $this->addFlash('alert', "Korisnik ima već $borrowed posudbe!");
        }

        return $this->redirectToRoute('posudbe_korisnika');

    }

    #[Route('knjiznica/gradja/vrati/{id}', name: 'vrati_gradju', methods: ['GET'])]
    public function vracanje($id, RezervacijaVerify $verify)
    {
        $verify->rezervacijaExpirationCheck();
        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $knjiznicar Knjiznice
         */
        $knjiznicar = $this->getUser();

        if ($knjiznicar) {
            if ($rezervacija->getKnjiznica() instanceof $knjiznicar ) {
                $rezervacija
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(6));
                $rezervacija->getGradja()
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(1));

                $rezervacija->setDatumVracanja((new DateTime())->add(new DateInterval('P0D')));

                $entityManager->flush();

                $this->addFlash('success', 'Građa uspješno vraćena!');

            }
            return $this->redirectToRoute('posudbe_korisnika');

        }
        return $this->render('app_login');
    }
}
