<?php

namespace App\Controller;

use App\Entity\Gradja;
use App\Entity\Knjiznice;
use App\Form\GradjaType;
use App\Repository\GradjaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/knjiznica/gradja')]
class GradjaController extends AbstractController
{
    #[Route('/', name: 'gradja_index', methods: ['GET'])]
    public function index(GradjaRepository $gradjaRepository): Response
    {
        /**
         * @var $user Knjiznice
         */
        $user = $this->getUser();
        $user->getOibKnjiznice();
        return $this->render('gradja/index.html.twig', [
            'gradjas' => $gradjaRepository->findBy([
                'knjiznicaVlasnik' => $user
            ]),
        ]);
    }

    #[Route('/new', name: 'gradja_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $gradja = new Gradja();
        $form = $this->createForm(GradjaType::class, $gradja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            /**
             * @var $user Knjiznice
             */
            $user = $this->getUser();
            $gradja->setKnjiznicaVlasnik($user);

            if($request->files->get('gradja')['fotografija'] !== null){
                $gradja->setFotografija($this->tempUploadAction($request));
            }

            $entityManager->persist($gradja);
            $entityManager->flush();

            return $this->redirectToRoute('gradja_index');
        }

        return $this->render('gradja/new.html.twig', [
            'gradja' => $gradja,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'gradja_show', methods: ['GET'])]
    public function show(Gradja $gradja): Response
    {
        return $this->render('gradja/show.html.twig', [
            'gradja' => $gradja,
        ]);
    }

    #[Route('/{id}/edit', name: 'gradja_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gradja $gradja): Response
    {
        $form = $this->createForm(GradjaType::class, $gradja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gradja_index');
        }

        return $this->render('gradja/edit.html.twig', [
            'gradja' => $gradja,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'gradja_delete', methods: ['POST'])]
    public function delete(Request $request, Gradja $gradja): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gradja->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gradja);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gradja_index');
    }

    private function tempUploadAction(Request $req){

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $req->files->get('gradja')['fotografija'];
        $destination = $this->getParameter('kernel.project_dir').'/public/files/pitcures/gradja';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move($destination, $newFileName);

        return '/files/pitcures/gradja/'.$newFileName;
    }
}
