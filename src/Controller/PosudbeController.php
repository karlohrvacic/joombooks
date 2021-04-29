<?php

namespace App\Controller;

use App\Entity\Gradja;
use App\Entity\Korisnici;
use App\Entity\Posudbe;
use App\Entity\Statusi;
use App\Form\PosudbeType;
use App\Repository\GradjaRepository;
use App\Repository\PosudbeRepository;
use App\Repository\StatusiRepository;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/korisnik/posudbe')]
class PosudbeController extends AbstractController
{
    #[Route('/', name: 'posudbe_index', methods: ['GET'])]
    public function index(PosudbeRepository $posudbeRepository): Response
    {
        return $this->render('posudbe/index.html.twig', [
            'posudbes' => $posudbeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'posudbe_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
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

        if($request->isMethod('get')){
            $posudbe = new Posudbe();
            $entityManager = $this->getDoctrine()->getManager();
            $gradja = $entityManager->getRepository(Gradja::class)
                ->find($request->query->get('id'));

            /**
             * @var $user Korisnici
             */
            $user = $this->getUser();

            //todo logika smije li produljiti

            $posudbe->setIdGradje($gradja->getId());
            $posudbe->setGradja($gradja);
            $posudbe->setKorisnici($user);
            $posudbe->setStatus($entityManager->getRepository(Statusi::class)->find(5));

            $posudbe->setDatumPosudbe((new DateTime())->add(new DateInterval('P2D')));
            $posudbe->setDatumRokaVracanja((new DateTime())->add(new DateInterval('P32D')));
            $posudbe->setBrojIskazniceKorisnika($user->getBrojIskazniceKorisnika());
            $gradja->setStatus($entityManager->getRepository(Statusi::class)->find(5));


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($posudbe);
            $entityManager->persist($gradja);


            $user->addPosudbe($posudbe);
            $entityManager->persist($user);

            $entityManager->flush();


            return $this->redirectToRoute('posudjene_knjige_korisnika');

        }
    }

    #[Route('/{id}', name: 'posudbe_show', methods: ['GET'])]
    public function show(Posudbe $posudbe): Response
    {
        return $this->render('posudbe/show.html.twig', [
            'posudbe' => $posudbe,
        ]);
    }

    #[Route('/{id}/edit', name: 'posudbe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Posudbe $posudbe): Response
    {
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
    public function delete(Request $request, Posudbe $posudbe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$posudbe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($posudbe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('posudbe_index');
    }
}
