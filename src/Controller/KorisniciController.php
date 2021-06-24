<?php

namespace App\Controller;

use App\Entity\Gradja;
use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Entity\Posudbe;
use App\Entity\ResetPasswordRequest;
use App\Entity\Statusi;
use App\Form\KorisniciType;
use App\Repository\KorisniciRepository;
use App\Service\MailerSender;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/knjiznica/korisnici')]
class KorisniciController extends AbstractController
{
    private $flasher;
    private UserPasswordHasherInterface $passwordEncoder;


    public function __construct(ToastrFactory $flasher, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->flasher = $flasher;
        $this->passwordEncoder = $passwordEncoder;

    }



    #[Route('/', name: 'korisnici_index', methods: ['GET'])]
    public function index(KorisniciRepository $korisniciRepository): Response
    {
        return $this->render('korisnici/index.html.twig', [
            'korisnicis' => $korisniciRepository->findAll(),
            'knjiznica' => $this->getUser(),

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
                /**
                 * @var Knjiznice $knjiznica
                 */
                $knjiznica = $this->getUser();
                $korisnici->setKnjiznice($knjiznica);
            }

            if($request->files->get('korisnici')['fotografija'] !== null){
                $korisnici->setFotografija($this->tempUploadAction($request));
            }

            // Save random string as activation code
            $code = uniqid();
            $korisnici->setLozinka($this->passwordEncoder->hashPassword($korisnici, $code));

            $entityManager->persist($korisnici);
            $entityManager->flush();

            // Send user activation code via email
            $mailerSender->sendActivationEmail($korisnici, $code);

            $this->flasher->addSuccess('Novi korisnik uspješno pohranjen!');
            $this->flasher->addInfo('Aktivacijski kod je korisniku poslan putem e-maila!');

            return $this->redirectToRoute('korisnici_index');
        }

        return $this->render('korisnici/new.html.twig', [
            'korisnici' => $korisnici,
            'form' => $form->createView(),
            'knjiznica' => $this->getUser(),

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

            $this->flasher->addSuccess('Promjene uspješno pohranjene!');

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

            /**
             * @var $posudbe Posudbe
             */
            $posudbe = $this->getDoctrine()->getManager()->getRepository(Posudbe::class)->findBy([
                'korisnici' => $korisnici,
            ]);


            /**
             * Removes all Posudbe from Korisnik, all history is deleted before user
             * @var $posudba Posudbe
             */
            foreach ($posudbe as $posudba)  {
                $statusId = $posudba->getStatus()->getId();
                if ($statusId == 6){
                    $entityManager->remove($posudba);
                } elseif ($statusId == 5){
                    /**
                     * @var $rezervacija Gradja
                     */
                    $rezervacija = $this->getDoctrine()->getManager()->getRepository(Gradja::class)->findOneBy([
                        'id' => $posudba->getGradja()->getId(),
                    ]);

                    /**
                     * @var $status Statusi
                     */

                    $status = $this->getDoctrine()->getManager()->getRepository(Statusi::class)->findOneBy(['id' => 1]);

                    $rezervacija->setStatus($status);

                    $this->getDoctrine()->getManager()->persist($rezervacija);
                    $this->getDoctrine()->getManager()->flush();
                    $entityManager->remove($posudba);

                } elseif ($statusId == 3 || $statusId == 9){
                    $this->flasher->addWarning('Korisnik ima posuđenih knjiga! Korisnik nije obrisan!');
                    return $this->redirectToRoute('korisnici_index');
                }
            }

            /**
             * @var $passwordRequest ResetPasswordRequest
             */
                $passwordRequest = $this->getDoctrine()->getManager()->getRepository(ResetPasswordRequest::class)->
                findBy(['user' => $korisnici]);

            foreach ($passwordRequest as $requ) {
                $entityManager->remove($requ);
            }

            $entityManager->remove($korisnici);
            $entityManager->flush();
            $this->flasher->addSuccess('Korisnik uspješno uklonjen!');
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
