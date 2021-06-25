<?php

namespace App\Controller;

use App\Entity\Gradja;
use App\Entity\Knjiznice;
use App\Entity\Posudbe;
use App\Entity\Statusi;
use App\Form\GradjaType;
use App\Repository\GradjaRepository;
use App\Service\RezervacijaVerify;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/knjiznica/gradja')]
class GradjaController extends AbstractController
{
    private $flasher;

    public function __construct(ToastrFactory $flasher, RezervacijaVerify $verify)
    {
        $this->flasher = $flasher;
        $verify->rezervacijaExpirationCheck();
    }

    #[Route('/', name: 'gradja_index', methods: ['GET'])]
    public function index(GradjaRepository $gradjaRepository): Response
    {
        /**
         * @var $user Knjiznice
         */
        $user = $this->getUser();
        $code = new BarcodeController();

        return $this->render('gradja/index.html.twig', [
            'gradjas' => $gradjaRepository->findBy([
                'knjiznicaVlasnik' => $user]),
            'knjiznica' => $this->getUser(),
            'code' => $code,
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

            $this->flasher->addSuccess('Nova knjiga uspješno pohranjena!');

            return $this->redirectToRoute('gradja_index');
        }

        return $this->render('gradja/new.html.twig', [
            'gradja' => $gradja,
            'form' => $form->createView(),
            'knjiznica' => $this->getUser(),
        ]);
    }

    #[Route('/{id}', name: 'gradja_show', methods: ['GET'])]
    public function show(Gradja $gradja): Response
    {
        $code = new BarcodeController();
        return $this->render('gradja/show.html.twig', [
            'gradja' => $gradja,
            'knjiznica' => $this->getUser(),
            'code' => $code,

        ]);
    }

    #[Route('/{id}/edit', name: 'gradja_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gradja $gradja): Response
    {
        $form = $this->createForm(GradjaType::class, $gradja);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if($request->files->get('gradja')['fotografija'] != null){
                if($gradja->getFotografija() != null && file_exists("../public".$gradja->getFotografija())){
                    unlink("../public".$gradja->getFotografija());
                }
                $gradja->setFotografija($this->tempUploadAction($request));
            }

            $this->getDoctrine()->getManager()->flush();

            $this->flasher->addSuccess('Promjene uspješno pohranjene!');

            return $this->redirectToRoute('gradja_index');
        }

        return $this->render('gradja/edit.html.twig', [
            'gradja' => $gradja,
            'form' => $form->createView(),
            'knjiznica' => $this->getUser(),

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

        $this->flasher->addSuccess('Knjiga uspješno uklonjena!');

        return $this->redirectToRoute('gradja_index');
    }

    private function tempUploadAction(Request $req): string
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $req->files->get('gradja')['fotografija'];
        $destination = $this->getParameter('kernel.project_dir').'/public/files/pitcures/gradja';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move($destination, $newFileName);

        return '/files/pitcures/gradja/'.$newFileName;
    }

    #[Route('gradja/posudi/{id}', name: 'posudi_gradju', methods: ['GET'])]
    public function posudiKnjigu($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $knjiznicar Knjiznice
         */
        $knjiznicar = $this->getUser();
        if ($knjiznicar){
            if($knjiznicar === $rezervacija->getKnjiznica()) {
                $rezervacija
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(3));
                $rezervacija->getGradja()
                    ->setStatus($entityManager->getRepository(Statusi::class)
                        ->find(3));

                $entityManager->flush();
                $this->flasher->addSuccess('Knjiga uspješno posuđena!');

            }

            return $this->redirectToRoute('rezervirane_knjige_korisnika');

        }
        return $this->redirectToRoute('rezervirane_knjige_korisnika');

    }
}
