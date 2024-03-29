<?php

namespace App\Controller;

use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Entity\Posudbe;
use App\Entity\Statusi;
use App\Service\RezervacijaVerify;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{

    private $flasher;

    public function __construct(ToastrFactory $flasher, RezervacijaVerify $verify)
    {
        $this->flasher = $flasher;
        $verify->rezervacijaExpirationCheck();
    }

    /**
     * @Route("/")
     */
    public function index(): RedirectResponse
    {

        /** @var $user Korisnici */
        $user = $this->getUser();
        if($user instanceof Korisnici){
            return $this->redirectToRoute('korisnicki_izbornik');
        }
        /** @var $user Knjiznice */
        $user = $this->getUser();
        if($user instanceof Knjiznice){
            return $this->redirectToRoute('knjiznica_izbornik');
        }

        return $this->redirectToRoute('app_login');

    }

    /**
     * @Route("radno_vrijeme", name="radno_vrijeme")
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
    public function cancelation($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $user Korisnici
         */
        $user = $this->getUser();
        if ($user instanceof Korisnici) {
            if ($user->getBrojIskazniceKorisnika() == $rezervacija->getKorisnici()->getBrojIskazniceKorisnika()) {
                $rezervacija
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(8));
                $rezervacija->getGradja()
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(1));

                $entityManager->flush();

                $this->flasher->addSuccess('Rezervacija uspješno otkazana!');

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

                $this->flasher->addSuccess('Rezervacija uspješno otkazana!');

            }
            return $this->redirectToRoute('rezervacije_korisnika');
        }
        return $this->redirectToRoute('app_login');
    }

}