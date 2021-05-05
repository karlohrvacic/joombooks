<?php

namespace App\Controller;

use App\Entity\Jezici;
use App\Form\JeziciType;
use App\Repository\JeziciRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/knjiznica/jezici')]
class JeziciController extends AbstractController
{
    #[Route('/', name: 'jezici_index', methods: ['GET'])]
    public function index(JeziciRepository $jeziciRepository): Response
    {
        return $this->render('jezici/index.html.twig', [
            'jezicis' => $jeziciRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'jezici_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $jezici = new Jezici();
        $form = $this->createForm(JeziciType::class, $jezici);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jezici);
            $entityManager->flush();
            $this->addFlash('success', 'Novi jezik uspješno pohranjen!');
            return $this->redirectToRoute('jezici_index');
        }

        return $this->render('jezici/new.html.twig', [
            'jezici' => $jezici,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'jezici_show', methods: ['GET'])]
    public function show(Jezici $jezici): Response
    {
        return $this->render('jezici/show.html.twig', [
            'jezici' => $jezici,
        ]);
    }

    #[Route('/{id}/edit', name: 'jezici_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Jezici $jezici): Response
    {
        $form = $this->createForm(JeziciType::class, $jezici);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Promjene uspješno pohranjene!');

            return $this->redirectToRoute('jezici_index');
        }

        return $this->render('jezici/edit.html.twig', [
            'jezici' => $jezici,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'jezici_delete', methods: ['POST'])]
    public function delete(Request $request, Jezici $jezici): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jezici->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($jezici);
            $entityManager->flush();

            $this->addFlash('success', 'Autor uspješno uklonjen!');

        }

        return $this->redirectToRoute('jezici_index');
    }
}
