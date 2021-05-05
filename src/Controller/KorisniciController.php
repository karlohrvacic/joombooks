<?php

namespace App\Controller;

use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Form\KorisniciType;
use App\Repository\KorisniciRepository;
use App\Service\MailerSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    public function new(Request $request, MailerSender $mailerSender): Response
    {
        $korisnici = new Korisnici();
        $form = $this->createForm(KorisniciType::class, $korisnici);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $korisnici->setRoles(["ROLE_USER"]);

            if(is_a($this->getUser(), Knjiznice::class)){
                $knjiznica = $this->getUser();
                $korisnici->setKnjiznice($knjiznica);
            }

            if($request->files->get('fotografija') !== null){
                $korisnici->setFotografija($this->tempUploadAction($request));
            }

            // Save random string as activation code
            $korisnici->setLozinka(uniqid());

            $entityManager->persist($korisnici);
            $entityManager->flush();

            // Send user activation code via email
            $mailerSender->sendActivationEmail($korisnici);

            $this->addFlash('success', 'Novi korisnik uspješno pohranjen!');
            $this->addFlash('success', 'Aktivacijski kod mu je poslan putem e-maila!');

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

            if($request->files->get('korisnici')['fotografija'] != null){
                if($korisnici->getFotografija() != null && file_exists("../public".$korisnici->getFotografija())){
                    unlink("../public".$korisnici->getFotografija());
                }
                $korisnici->setFotografija($this->tempUploadAction($request));
            }

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Promjene uspješno pohranjene!');

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
            $this->addFlash('success', 'Korisnik uspješno uklonjen!');
        }

        return $this->redirectToRoute('korisnici_index');
    }

    private function tempUploadAction(Request $req): string
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $req->files->get('korisnici')['fotografija'];
        $destination = $this->getParameter('kernel.project_dir').'/public/files/pitcures/profile';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move($destination, $newFileName);

        return '/files/pitcures/profile/'.$newFileName;
    }
}
