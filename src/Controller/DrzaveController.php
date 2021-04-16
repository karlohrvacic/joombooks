<?php

namespace App\Controller;

use App\Entity\Drzave;
use App\Form\DrzaveType;
use App\Repository\DrzaveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/drzave")
 */
class DrzaveController extends AbstractController
{
    /**
     * @Route("/", name="drzave_index", methods={"GET"})
     */
    public function index(DrzaveRepository $drzaveRepository): Response
    {
        return $this->render('drzave/index.html.twig', [
            'drzaves' => $drzaveRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="drzave_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $drzave = new Drzave();
        $form = $this->createForm(DrzaveType::class, $drzave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($drzave);
            $entityManager->flush();

            return $this->redirectToRoute('drzave_index');
        }

        return $this->render('drzave/new.html.twig', [
            'drzave' => $drzave,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="drzave_show", methods={"GET"})
     */
    public function show(Drzave $drzave): Response
    {
        return $this->render('drzave/show.html.twig', [
            'drzave' => $drzave,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="drzave_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Drzave $drzave): Response
    {
        $form = $this->createForm(DrzaveType::class, $drzave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('drzave_index');
        }

        return $this->render('drzave/edit.html.twig', [
            'drzave' => $drzave,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="drzave_delete", methods={"POST"})
     */
    public function delete(Request $request, Drzave $drzave): Response
    {
        if ($this->isCsrfTokenValid('delete'.$drzave->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($drzave);
            $entityManager->flush();
        }

        return $this->redirectToRoute('drzave_index');
    }


}
