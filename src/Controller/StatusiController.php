<?php

namespace App\Controller;

use App\Entity\Statusi;
use App\Form\StatusiType;
use App\Repository\StatusiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/knjiznica/statusi')]
class StatusiController extends AbstractController
{
    #[Route('/', name: 'statusi_index', methods: ['GET'])]
    public function index(StatusiRepository $statusiRepository): Response
    {
        return $this->render('statusi/index.html.twig', [
            'statusis' => $statusiRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'statusi_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $statusi = new Statusi();
        $form = $this->createForm(StatusiType::class, $statusi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($statusi);
            $entityManager->flush();

            $this->addFlash('success', 'Novi status uspješno pohranjen!');

            return $this->redirectToRoute('statusi_index');
        }

        return $this->render('statusi/new.html.twig', [
            'statusi' => $statusi,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'statusi_show', methods: ['GET'])]
    public function show(Statusi $statusi): Response
    {
        return $this->render('statusi/show.html.twig', [
            'statusi' => $statusi,
        ]);
    }

    #[Route('/{id}/edit', name: 'statusi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Statusi $statusi): Response
    {
        $form = $this->createForm(StatusiType::class, $statusi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Promjene uspješno pohranjene!');

            return $this->redirectToRoute('statusi_index');
        }

        return $this->render('statusi/edit.html.twig', [
            'statusi' => $statusi,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'statusi_delete', methods: ['POST'])]
    public function delete(Request $request, Statusi $statusi): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statusi->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($statusi);
            $entityManager->flush();
            $this->addFlash('success', 'Status uspješno uklonjen!');
        }

        return $this->redirectToRoute('statusi_index');
    }
}
