<?php

namespace App\Controller;

use App\Entity\Clanstva;
use App\Form\ClanstvaType;
use App\Repository\ClanstvaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/clanstva')]
class ClanstvaController extends AbstractController
{
    #[Route('/', name: 'clanstva_index', methods: ['GET'])]
    public function index(ClanstvaRepository $clanstvaRepository): Response
    {
        return $this->render('clanstva/index.html.twig', [
            'clanstvas' => $clanstvaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'clanstva_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $clanstva = new Clanstva();
        $form = $this->createForm(ClanstvaType::class, $clanstva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($clanstva);
            $entityManager->flush();

            return $this->redirectToRoute('clanstva_index');
        }

        return $this->render('clanstva/new.html.twig', [
            'clanstva' => $clanstva,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'clanstva_show', methods: ['GET'])]
    public function show(Clanstva $clanstva): Response
    {
        return $this->render('clanstva/show.html.twig', [
            'clanstva' => $clanstva,
        ]);
    }

    #[Route('/{id}/edit', name: 'clanstva_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Clanstva $clanstva): Response
    {
        $form = $this->createForm(ClanstvaType::class, $clanstva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('clanstva_index');
        }

        return $this->render('clanstva/edit.html.twig', [
            'clanstva' => $clanstva,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'clanstva_delete', methods: ['POST'])]
    public function delete(Request $request, Clanstva $clanstva): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clanstva->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($clanstva);
            $entityManager->flush();
        }

        return $this->redirectToRoute('clanstva_index');
    }
}
