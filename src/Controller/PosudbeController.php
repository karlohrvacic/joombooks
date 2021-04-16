<?php

namespace App\Controller;

use App\Entity\Posudbe;
use App\Form\PosudbeType;
use App\Repository\PosudbeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posudbe')]
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
