<?php

namespace App\Controller;

use App\Entity\Korisnici;
use App\Form\KorisniciType;
use App\Repository\KorisniciRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/knjiznica/korisnici')]
class KorisniciController extends AbstractController
{
    #[Route('/', name: 'korisnici_index', methods: ['GET'])]
    public function index(KorisniciRepository $korisniciRepository): Response
    {
        return $this->render('korisnici/index.html.twig', [
            'korisnicis' => $korisniciRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'korisnici_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $korisnici = new Korisnici();
        $form = $this->createForm(KorisniciType::class, $korisnici);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($korisnici);
            $entityManager->flush();

            return $this->redirectToRoute('korisnici_index');
        }

        return $this->render('korisnici/new.html.twig', [
            'korisnici' => $korisnici,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'korisnici_show', methods: ['GET'])]
    public function show(Korisnici $korisnici): Response
    {
        return $this->render('korisnici/show.html.twig', [
            'korisnici' => $korisnici,
        ]);
    }

    #[Route('/{id}/edit', name: 'korisnici_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Korisnici $korisnici): Response
    {
        $form = $this->createForm(KorisniciType::class, $korisnici);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('korisnici_index');
        }

        return $this->render('korisnici/edit.html.twig', [
            'korisnici' => $korisnici,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'korisnici_delete', methods: ['POST'])]
    public function delete(Request $request, Korisnici $korisnici): Response
    {
        if ($this->isCsrfTokenValid('delete'.$korisnici->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($korisnici);
            $entityManager->flush();
        }

        return $this->redirectToRoute('korisnici_index');
    }
}
