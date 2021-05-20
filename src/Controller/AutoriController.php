<?php

namespace App\Controller;

use App\Entity\Autori;
use App\Form\AutoriType;
use App\Repository\AutoriRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/knjiznica/autori")
 */
class AutoriController extends AbstractController
{
    /**
     * @Route("/", name="autori_index", methods={"GET"})
     */
    public function index(AutoriRepository $autoriRepository): Response
    {
        return $this->render('autori/index.html.twig', [
            'autoris' => $autoriRepository->findAll(),
            'knjiznica' => $this->getUser()
        ]);
    }

    /**
     * @Route("/new", name="autori_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $autori = new Autori();
        $form = $this->createForm(AutoriType::class, $autori);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($autori);
            $entityManager->flush();

            $this->addFlash('success', 'Novi autor uspješno pohranjen!');

            return $this->redirectToRoute('autori_index');
        }

        return $this->render('autori/new.html.twig', [
            'autori' => $autori,
            'form' => $form->createView(),
            'knjiznica' => $this->getUser()
        ]);
    }

    /**
     * @Route("/{id}", name="autori_show", methods={"GET"})
     */
    public function show(Autori $autori): Response
    {
        return $this->render('autori/show.html.twig', [
            'autori' => $autori,
            'knjiznica' => $this->getUser()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="autori_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Autori $autori): Response
    {
        $form = $this->createForm(AutoriType::class, $autori);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Promjene uspješno pohranjene!');

            return $this->redirectToRoute('autori_index');
        }

        return $this->render('autori/edit.html.twig', [
            'autori' => $autori,
            'form' => $form->createView(),
            'knjiznica' => $this->getUser()
        ]);
    }

    /**
     * @Route("/{id}", name="autori_delete", methods={"POST"})
     */
    public function delete(Request $request, Autori $autori): Response
    {
        if ($this->isCsrfTokenValid('delete'.$autori->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($autori);
            $entityManager->flush();
            $this->addFlash('success', 'Autor uspješno uklonjen!');

        }

        return $this->redirectToRoute('autori_index');
    }
}
