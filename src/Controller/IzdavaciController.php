<?php

namespace App\Controller;

use App\Entity\Izdavaci;
use App\Form\IzdavaciType;
use App\Repository\IzdavaciRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/knjiznica/izdavaci')]
class IzdavaciController extends AbstractController
{
    #[Route('/', name: 'izdavaci_index', methods: ['GET'])]
    public function index(IzdavaciRepository $izdavaciRepository): Response
    {
        return $this->render('izdavaci/index.html.twig', [
            'izdavacis' => $izdavaciRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'izdavaci_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $izdavaci = new Izdavaci();
        $form = $this->createForm(IzdavaciType::class, $izdavaci);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($izdavaci);
            $entityManager->flush();

            return $this->redirectToRoute('izdavaci_index');
        }

        return $this->render('izdavaci/new.html.twig', [
            'izdavaci' => $izdavaci,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'izdavaci_show', methods: ['GET'])]
    public function show(Izdavaci $izdavaci): Response
    {
        return $this->render('izdavaci/show.html.twig', [
            'izdavaci' => $izdavaci,
        ]);
    }

    #[Route('/{id}/edit', name: 'izdavaci_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Izdavaci $izdavaci): Response
    {
        $form = $this->createForm(IzdavaciType::class, $izdavaci);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('izdavaci_index');
        }

        return $this->render('izdavaci/edit.html.twig', [
            'izdavaci' => $izdavaci,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'izdavaci_delete', methods: ['POST'])]
    public function delete(Request $request, Izdavaci $izdavaci): Response
    {
        if ($this->isCsrfTokenValid('delete'.$izdavaci->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($izdavaci);
            $entityManager->flush();
        }

        return $this->redirectToRoute('izdavaci_index');
    }
}
