<?php

namespace App\Controller;

use App\Entity\Gradja;
use App\Entity\Korisnici;
use App\Entity\Posudbe;
use App\Entity\Statusi;
use App\Form\PosudbeType;
use App\Repository\PosudbeRepository;
use App\Service\RezervacijaVerify;
use DateInterval;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/korisnik/posudbe')]
class PosudbeController extends AbstractController
{
    #[Route('/', name: 'posudbe_index', methods: ['GET'])]
    public function index(PosudbeRepository $posudbeRepository, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        return $this->render('posudbe/index.html.twig', [
            'posudbes' => $posudbeRepository->findAll(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/new/{id}', name: 'posudbe_new', methods: ['GET'])]
    public function new($id, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();

        //Rezervacije
        $posudbe = new Posudbe();
        $entityManager = $this->getDoctrine()->getManager();
        $gradja = $entityManager->getRepository(Gradja::class)->find($id);

        /**
         * @var $user Korisnici
         */
        $user = $this->getUser();

        // smije li napraviti rezervaciju

        $maxRezerviranih = $user->getKnjiznice()->getMaxRezerviranih();

        if($user->getBrojTrenutnoRezerviranih() < $maxRezerviranih){

            $posudbe->setGradja($gradja);
            $posudbe->setKorisnici($user);
            $posudbe->setStatus($entityManager->getRepository(Statusi::class)->find(5));

            $posudbe->setDatumPosudbe((new DateTime())->add(new DateInterval('P0D')));
            $posudbe->setKnjiznica($user->getKnjiznice());
            $daniRezervacije = $user->getKnjiznice()->getDaniRezervacije();
            $duration = "P".$daniRezervacije."D";

            $posudbe->setDatumRokaVracanja((new DateTime())->add(new DateInterval($duration)));
            $posudbe->setBrojIskazniceKorisnika($user->getBrojIskazniceKorisnika());
            $gradja->setStatus($entityManager->getRepository(Statusi::class)->find(5));
            $user->addPosudbe($posudbe);

            $entityManager->persist($posudbe);
            $entityManager->persist($gradja);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Uspješno ste rezervirali građu!');
            } else{
                $this->addFlash('alert', "Već ste rezervirali $maxRezerviranih knjige!");
            }

        return $this->redirectToRoute('rezervirane_knjige_korisnika');
    }

    #[Route('/{id}', name: 'posudbe_show', methods: ['GET'])]
    public function show(Posudbe $posudbe, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        return $this->render('posudbe/show.html.twig', [
            'posudbe' => $posudbe,
        ]);
    }

    #[Route('/{id}/edit', name: 'posudbe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Posudbe $posudbe, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        $form = $this->createForm(PosudbeType::class, $posudbe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Promjene uspješno pohranjene!');

            return $this->redirectToRoute('posudbe_index');
        }

        return $this->render('posudbe/edit.html.twig', [
            'posudbe' => $posudbe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'posudbe_delete', methods: ['POST'])]
    public function delete(Request $request, Posudbe $posudbe, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        if ($this->isCsrfTokenValid('delete'.$posudbe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($posudbe);
            $entityManager->flush();
            $this->addFlash('success', 'Uspješno uklonjeo!');
        }

        return $this->redirectToRoute('posudbe_index');
    }

    /**
     * @throws Exception
     */
    #[Route('/produlji-rezervaciju/{id}', name: 'rezervacija_extend', methods: ['GET'])]
    public function extension($id, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();

        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $user Korisnici
         */
        $user = $this->getUser();

        $daniRezervacije = $user->getKnjiznice()->getDaniRezervacije();
        if($user->getBrojIskazniceKorisnika() == $rezervacija->getBrojIskazniceKorisnika() &&
            $rezervacija->getDatumPosudbe()->diff($rezervacija->getDatumRokaVracanja())->format('%r%a') <
            ($daniRezervacije * 2) && $rezervacija->getStatus()->getId() == 5){

            $duration = "P".$daniRezervacije."D";
            $newDate = clone $rezervacija->getDatumRokaVracanja();
            $newDate->add(new DateInterval($duration));

            $rezervacija->setDatumRokaVracanja($newDate);
            $entityManager->persist($rezervacija);
            $entityManager->flush();

            $this->addFlash('success', 'Uspješno ste produžili rezervaciju!');
        } else{
            $this->addFlash('alert', "Rezervaciju ne možete produžiti!");
        }

        return $this->redirectToRoute('rezervirane_knjige_korisnika');

    }

    #[Route('/zatrazi-produljenje/{id}', name: 'zatrazi_produljenje_posudbe', methods: ['GET'])]
    public function zatraziProduljenjePosudbe($id, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $user Korisnici
         */
        $user = $this->getUser();
        if ($user->getBrojIskazniceKorisnika() == $rezervacija->getBrojIskazniceKorisnika()
            && $rezervacija->getStatus()->getId() == 3) {

            $rezervacija->setStatus($entityManager->getRepository(Statusi::class)->find(9));
                $entityManager->flush();

                $this->addFlash('success', 'Zahtjev za produljenje posudbe uspješno poslan!');
        }
        else{
            $this->addFlash('alert', 'Nije vam dopušteno poslati zahtjev za produljenje,!');
        }
            return $this->redirectToRoute('rezervirane_knjige_korisnika');

        return $this->redirectToRoute('app_login');
    }

}