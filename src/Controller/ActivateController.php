<?php

namespace App\Controller;

use App\Entity\Korisnici;
use App\Form\KorisniciType;
use App\Repository\KorisniciRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/activation')]
class ActivateController extends AbstractController
{
    #[Route('/', name: 'korisnici_index', methods: ['GET'])]
    public function index(Request $request, Korisnici $korisnici): Response
    {

    }

    #[Route('/new', name: 'korisnici_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $korisnici = new Korisnici();
        $form = $this->createForm(KorisniciType::class, $korisnici);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $korisnici->setRoles(["ROLE_USER"]);

            if (is_a($this->getUser(), Knjiznice::class)) {
                $korisnici->setKnjiznice($this->getUser());
            }

            if ($request->files->get('fotografija') !== null) {
                $korisnici->setFotografija($this->tempUploadAction($request));
            }

            $korisnici->setLozinka(uniqid());

            //todo send email with code stored in password field

            $entityManager->persist($korisnici);
            $entityManager->flush();

            return $this->redirectToRoute('korisnici_index');
        }

        return $this->render('korisnici/new.html.twig', [
            'korisnici' => $korisnici,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'korisnici_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Korisnici $korisnici): Response
    {
        $form = $this->createForm(KorisniciType::class, $korisnici);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = password_hash($form->get('lozinka')->getData(), PASSWORD_DEFAULT);

            $korisnici->setPassword($hash);

            if ($request->files->get('korisnici')['fotografija'] != null) {
                $filesystem = new Filesystem();
                $filesystem->remove($korisnici->getFotografija());
                $korisnici->setFotografija($this->tempUploadAction($request));
            }

            $this->getDoctrine()->getManager()->flush();

            $hash = password_hash($form->get('lozinka')->getData(), PASSWORD_DEFAULT);

            $korisnici->setPassword($hash);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('korisnici_index');
        }

        return $this->render('korisnici/edit.html.twig', [
            'korisnici' => $korisnici,
            'form' => $form->createView(),
        ]);
    }
}