<?php

namespace App\Controller;

use App\Entity\Drzave;
use App\Form\DrzaveType;
use App\Repository\DrzaveRepository;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/knjiznica/drzave")
 */
class DrzaveController extends AbstractController
{
    private $flasher;

    public function __construct(ToastrFactory $flasher)
    {
        $this->flasher = $flasher;
    }

    /**
     * @Route("/", name="drzave_index", methods={"GET"})
     */
    public function index(DrzaveRepository $drzaveRepository): Response
    {
        return $this->render('drzave/index.html.twig', [
            'drzaves' => $drzaveRepository->findAll(),
            'knjiznica' => $this->getUser()
        ]);
    }

    /**
     * @Route("/new", name="drzave_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ToastrFactory $flasher): Response
    {
        $drzave = new Drzave();
        $form = $this->createForm(DrzaveType::class, $drzave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($drzave);
            $entityManager->flush();

            $this->flasher->addSuccess('Nova država uspješno pohranjena!');

            return $this->redirectToRoute('drzave_index');
        }

        return $this->render('drzave/new.html.twig', [
            'drzave' => $drzave,
            'form' => $form->createView(),
            'knjiznica' => $this->getUser()
        ]);
    }

    /**
     * @Route("/{id}", name="drzave_show", methods={"GET"})
     */
    public function show(Drzave $drzave): Response
    {
        return $this->render('drzave/show.html.twig', [
            'drzave' => $drzave,
            'knjiznica' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="drzave_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Drzave $drzave, ToastrFactory $flasher): Response
    {
        $form = $this->createForm(DrzaveType::class, $drzave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->flasher->addSuccess('Promjene uspješno pohranjene!');

            return $this->redirectToRoute('drzave_index');
        }

        return $this->render('drzave/edit.html.twig', [
            'drzave' => $drzave,
            'form' => $form->createView(),
            'knjiznica' => $this->getUser()
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
            $this->flasher->addSuccess('Država uspješno uklonjena!');

        }
        return $this->redirectToRoute('drzave_index');
    }


}
