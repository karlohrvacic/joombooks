<?php

namespace App\Controller;

use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Form\KorisniciType;
use App\Repository\KorisniciRepository;
use App\Service\MailerSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
                $korisnici->setKnjiznice($this->getUser());
            }

            if($request->files->get('fotografija') !== null){
                $korisnici->setFotografija($this->tempUploadAction($request));
            }

            $korisnici->setLozinka(uniqid());


            $entityManager->persist($korisnici);
            $entityManager->flush();

            $mailerSender->sendActivationEmail($korisnici);

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
            //$hash = password_hash($form->get('lozinka')->getData(), PASSWORD_DEFAULT);
            //$korisnici->setPassword($hash);

            if($request->files->get('korisnici')['fotografija'] != null){
                if($korisnici->getFotografija() != null){
                    unlink("../public".$korisnici->getFotografija());
                }
                $korisnici->setFotografija($this->tempUploadAction($request));
            }

            $this->getDoctrine()->getManager()->flush();

            //$hash = password_hash($form->get('lozinka')->getData(), PASSWORD_DEFAULT);

            //$korisnici->setPassword($hash);
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

    private function tempUploadAction(Request $req){

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $req->files->get('korisnici')['fotografija'];
        $destination = $this->getParameter('kernel.project_dir').'/public/files/pitcures/profile';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move($destination, $newFileName);

        return '/files/pitcures/profile/'.$newFileName;
    }
}
