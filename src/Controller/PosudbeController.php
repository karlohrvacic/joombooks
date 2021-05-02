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
    #[Route('/new', name: 'posudbe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        if ($request->isMethod('post')) {
            $posudbe = new Posudbe();
            $form = $this->createForm(PosudbeType::class, $posudbe);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($posudbe);
                $entityManager->flush();

                return $this->redirectToRoute('posudbe_index');
            }

            return $this->render('posudbe/new.html.twig', [
                'posudbe' => $posudbe,
                'form' => $form->createView(),
            ]);
        }

        //Rezervacije
        if($request->isMethod('get')){
            $posudbe = new Posudbe();
            $entityManager = $this->getDoctrine()->getManager();
            $gradja = $entityManager->getRepository(Gradja::class)
                ->find($request->query->get('id'));

            /**
             * @var $user Korisnici
             */
            $user = $this->getUser();


            // smije li napraviti rezervaciju
            if($user->getBrojTrenutnoRezerviranih() < $user->getKnjiznice()->getMaxRezerviranih()){

                $posudbe->setGradja($gradja);
                $posudbe->setKorisnici($user);
                $posudbe->setStatus($entityManager->getRepository(Statusi::class)->find(5));

                $posudbe->setDatumPosudbe((new DateTime())->add(new DateInterval('P0D')));

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
            }
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
        }

        return $this->redirectToRoute('posudbe_index');
    }

    #[Route('/cancel/{id}', name: 'rezervacija_cancel', methods: ['GET'])]
    public function cancelation($id, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();
        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $user Korisnici
         */
        $user = $this->getUser();
        if($user->getBrojIskazniceKorisnika() == $rezervacija->getBrojIskazniceKorisnika()){
            $rezervacija
                ->setStatus($entityManager->getRepository(Statusi::class)
                    ->find(8));
            $rezervacija->getGradja()
                ->setStatus($entityManager->getRepository(Statusi::class)
                    ->find(1));

            $entityManager->flush();
        }

        return $this->redirectToRoute('rezervirane_knjige_korisnika');

    }

    /**
     * @throws Exception
     */
    #[Route('/extend/{id}', name: 'rezervacija_extend', methods: ['GET'])]
    public function extension($id, RezervacijaVerify $verify): Response
    {
        $verify->rezervacijaExpirationCheck();

        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)
            ->find($id);

        /**
         * @var $user Korisnici
         */
        $user = $this->getUser();

        $daniRezervacije = $user->getKnjiznice()->getDaniRezervacije();
        if($user->getBrojIskazniceKorisnika() == $rezervacija->getBrojIskazniceKorisnika() &&
            $rezervacija->getDatumPosudbe()->diff($rezervacija->getDatumRokaVracanja())->format('%r%a') <
            ($daniRezervacije * 2)){

            $duration = "P".$daniRezervacije."D";

            $newDate = clone $rezervacija->getDatumRokaVracanja();
            $newDate->add(new DateInterval($duration));
            $rezervacija->setDatumRokaVracanja($newDate);
            $entityManager->persist($rezervacija);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rezervirane_knjige_korisnika');

    }

}