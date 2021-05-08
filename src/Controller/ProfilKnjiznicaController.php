<?php

namespace App\Controller;

use App\Entity\Gradja;
use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Entity\Posudbe;
use App\Entity\Statusi;
use App\Service\RezervacijaVerify;
use DateInterval;
use DateTime;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/knjiznica')]
class ProfilKnjiznicaController extends AbstractController
{

    /**
     * @Route("/", name="knjiznica_izbornik")
     */
    public function knjiznicaProfil(): Response
    {
        return $this->render('knjiznicniProfil/knjiznicaPocetna.html.twig');
    }

    /**
     * @Route("/rezervirano", name="rezervacije_korisnika")
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
     * @Route("/posudjeno", name="posudbe_korisnika")
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
            'status'  => array(3, 9)
        ], [
            'status' => 'DESC'
        ]);

        $code = new BarcodeController();

        return $this->render('knjiznicniProfil/posudjene.html.twig', [
            'posudbes' => $posudbe,
            'code' => $code
        ]);
    }

    #[Route('/gradja/posudi/{id}', name: 'posudi_rezerviranu_gradju', methods: ['GET'])]
    public function posudba($id, RezervacijaVerify $verify): RedirectResponse
    {
        $verify->rezervacijaExpirationCheck();
        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $knjiznicar Knjiznice
         */
        $knjiznicar = $this->getUser();

        if ($knjiznicar === $rezervacija->getKnjiznica()) {

            $rezervacija->setStatus($entityManager->getRepository(Statusi::class)->find(3));
            $rezervacija->getGradja()->setStatus($entityManager->getRepository(Statusi::class)->find(3));

            $rezervacija->setDatumPosudbe((new DateTime())->add(new DateInterval('P0D')));
            $daniPosudbe = $knjiznicar->getDaniPosudbe();
            $duration = "P" . $daniPosudbe . "D";

            $rezervacija->setDatumRokaVracanja((new DateTime())->add(new DateInterval($duration)));

            $entityManager->flush();
            $this->addFlash('success', 'Građa uspješno posuđena!');

            return $this->redirectToRoute('posudbe_korisnika');
        }
        $this->addFlash('alert', 'Nije vam dozvoljeno posuđivati tuđe knjige!');

        return $this->redirectToRoute('app_login');
    }


    #[Route('/gradja/posudi/{idGradja}/{idKorisnika}', name: 'posudi_gradju', methods: ['GET'])]
    public function posudbaBezRezervacije($idGradja, $idKorisnika, RezervacijaVerify $verify): RedirectResponse
    {
        $verify->rezervacijaExpirationCheck();
        $entityManager = $this->getDoctrine()->getManager();
        $korisnik = $entityManager->getRepository(Korisnici::class)->find($idKorisnika);

        $gradja = $entityManager->getRepository(Gradja::class)->find($idGradja);

        /**
         * @var $knjiznicar Knjiznice
         */
        $knjiznicar = $this->getUser();

        if ($korisnik->getBrojTrenutnoPosudenih() < $knjiznicar->getMaxPosudjenih() && $gradja->getStatus()->getId() == 1) {
            $posudbe = new Posudbe();

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

    #[Route('/gradja/vrati/{id}', name: 'vrati_gradju', methods: ['GET'])]
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

    #[Route('/gradja/produlji-posudbu/{id}', name: 'odobri-produljenje', methods: ['GET'])]
    public function extendAccept($id, RezervacijaVerify $verify)
    {
        $verify->rezervacijaExpirationCheck();
        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $knjiznicar Knjiznice
         */
        $knjiznicar = $this->getUser();

        if ($rezervacija->getKnjiznica() instanceof $knjiznicar ) {

            $rezervacija->setStatus($entityManager->getRepository(Statusi::class)->find(3));

            $korisnik = $rezervacija->getKorisnici();
            $imeKnjige = $rezervacija->getGradja()->getNaslov();
            $korisnik->addNotifications("Produljenje posudbe za $imeKnjige je odobreno!");

            $daniPosudbe = $knjiznicar->getDaniPosudbe();
            $duration = "P".$daniPosudbe."D";

            $newDate = clone $rezervacija->getDatumRokaVracanja();
            $newDate->add(new DateInterval($duration));

            $rezervacija->setDatumRokaVracanja($newDate);
            $entityManager->persist($rezervacija);

            $entityManager->flush();

            $this->addFlash('success', 'Posudba uspješno produžena!');

            return $this->redirectToRoute('posudbe_korisnika');

        }
        return $this->render('app_login');
    }

    #[Route('/gradja/odbij-produljenje-posudbe/{id}', name: 'odbij-produljenje', methods: ['GET'])]
    public function extendDeny($id, RezervacijaVerify $verify)
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

                $rezervacija->setStatus($entityManager->getRepository(Statusi::class)->find(3));

                $imeKnjige = $rezervacija->getGradja()->getNaslov();
                $korisnik = $rezervacija->getKorisnici();
                $korisnik->addNotifications("Produljenje posudbe za $imeKnjige je odbijeno!");

                $entityManager->flush();

                $this->addFlash('success', 'Zahtjev za produljivanje posudbe uspješno odbijen!');

            }
            return $this->redirectToRoute('posudbe_korisnika');

        }
        return $this->render('app_login');
    }

}