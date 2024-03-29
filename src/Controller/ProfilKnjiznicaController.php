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
use Exception;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/knjiznica')]
class ProfilKnjiznicaController extends AbstractController
{

    private $flasher;

    public function __construct(ToastrFactory $flasher, RezervacijaVerify $verify)
    {
        $this->flasher = $flasher;
        $verify->rezervacijaExpirationCheck();
    }

    /**
     * @Route("/", name="knjiznica_izbornik")
     */
    public function knjiznicaProfil(): Response
    {
        return $this->render('knjiznicniProfil/knjiznicaPocetna.html.twig', [
            'knjiznica' => $this->getUser(),

        ]);
    }

    /**
     * @Route("/rezervirano", name="rezervacije_korisnika")
     */
    public function pregledRezervacija(): Response
    {
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
            'code' => $code,
            'knjiznica' => $knjiznica
        ]);
    }

    /**
     * @Route("/posudjeno", name="posudbe_korisnika")
     */
    public function pregledPosudbi(): Response
    {
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
            'code' => $code,
            'knjiznica' => $knjiznica

        ]);
    }

    #[Route('/gradja/posudi/{id}', name: 'posudi_rezerviranu_gradju', methods: ['GET'])]
    public function posudba($id): RedirectResponse
    {
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

            try {
                $rezervacija->setDatumRokaVracanja((new DateTime())->add(new DateInterval($duration)));
            } catch (Exception $e) {
            }

            $entityManager->flush();
            $this->flasher->addSuccess('Knjiga uspješno posuđena!');

            return $this->redirectToRoute('posudbe_korisnika');
        }
        $this->flasher->addError('Nije vam dozvoljeno posuđivati tuđe knjige!');

        return $this->redirectToRoute('app_login');
    }


    #[Route('/gradja/posudi/{idGradja}/{idKorisnika}', name: 'posudi_gradju', methods: ['GET'])]
    public function posudbaBezRezervacije($idGradja, $idKorisnika): RedirectResponse
    {
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

            try {
                $posudbe->setDatumRokaVracanja((new DateTime())->add(new DateInterval($duration)));
            } catch (Exception $e) {
            }
            $gradja->setStatus($entityManager->getRepository(Statusi::class)->find(3));
            $korisnik->addPosudbe($posudbe);

            $entityManager->persist($posudbe);
            $entityManager->persist($gradja);
            $entityManager->persist($korisnik);
            $entityManager->flush();


            $this->flasher->addSuccess('Knjiga uspješno posuđena!');

        } else {
            $borrowed = $korisnik->getBrojTrenutnoPosudenih();
            $this->flasher->addWarning("Korisnik ima već $borrowed posudbe!");
        }

        return $this->redirectToRoute('posudbe_korisnika');
    }

    #[Route('/gradja/vrati/{id}', name: 'vrati_gradju', methods: ['GET'])]
    public function vracanje($id): RedirectResponse|Response
    {
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

                $this->flasher->addSuccess('Knjiga uspješno vraćena!');

            }
            return $this->redirectToRoute('posudbe_korisnika');

        }
        return $this->render('app_login', [
            'knjiznica' => $knjiznicar,
        ]);
    }

    #[Route('/gradja/produlji-posudbu/{id}', name: 'odobri-produljenje', methods: ['GET'])]
    public function extendAccept($id): RedirectResponse|Response
    {
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

            $newDate = new DateTime('Now');
            try {
                $newDate->add(new DateInterval($duration));
            } catch (Exception $e) {

            }

            $rezervacija->setDatumRokaVracanja($newDate);
            $entityManager->persist($rezervacija);

            $entityManager->flush();

            $this->flasher->addSuccess('Posudba uspješno produžena!');

            return $this->redirectToRoute('posudbe_korisnika');

        }
        return $this->render('app_login');
    }

    #[Route('/gradja/odbij-produljenje-posudbe/{id}', name: 'odbij-produljenje', methods: ['GET'])]
    public function extendDeny($id): RedirectResponse|Response
    {
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

                $this->flasher->addSuccess('Zahtjev za produljivanje posudbe uspješno odbijen!');

            }
            return $this->redirectToRoute('posudbe_korisnika');

        }
        return $this->render('app_login');
    }

}