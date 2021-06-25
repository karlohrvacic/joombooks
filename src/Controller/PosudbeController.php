<?php

namespace App\Controller;

use App\Entity\Gradja;
use App\Entity\Korisnici;
use App\Entity\Posudbe;
use App\Entity\Statusi;
use App\Form\PosudbeType;
use App\Repository\PosudbeRepository;
use App\Service\RezervacijaVerify;
use DateInterval;
use DateTime;
use Exception;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/korisnik/posudbe')]
class PosudbeController extends AbstractController
{

    private $flasher;

    public function __construct(ToastrFactory $flasher, RezervacijaVerify $verify)
    {
        $this->flasher = $flasher;
        $verify->rezervacijaExpirationCheck();

    }
    
    #[Route('/', name: 'posudbe_index', methods: ['GET'])]
    public function index(PosudbeRepository $posudbeRepository): Response
    {
        return $this->render('posudbe/index.html.twig', [
            'posudbes' => $posudbeRepository->findAll(),
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('/new/{id}', name: 'posudbe_new', methods: ['GET'])]
    public function new($id): Response
    {

        //Rezervacije
        $posudbe = new Posudbe();
        $entityManager = $this->getDoctrine()->getManager();
        $gradja = $entityManager->getRepository(Gradja::class)->find($id);

        /**
         * @var $user Korisnici
         */
        $user = $this->getUser();

        // smije li napraviti rezervaciju

        $maxRezerviranih = $user->getKnjiznice()->getMaxRezerviranih();

        if($user->getBrojTrenutnoRezerviranih() < $maxRezerviranih){

            $posudbe->setGradja($gradja);
            $posudbe->setKorisnici($user);
            $posudbe->setStatus($entityManager->getRepository(Statusi::class)->find(5));

            $posudbe->setDatumPosudbe((new DateTime())->add(new DateInterval('P0D')));
            $posudbe->setKnjiznica($user->getKnjiznice());
            $daniRezervacije = $user->getKnjiznice()->getDaniRezervacije();
            $duration = "P".$daniRezervacije."D";

            $posudbe->setDatumRokaVracanja((new DateTime())->add(new DateInterval($duration)));
            $gradja->setStatus($entityManager->getRepository(Statusi::class)->find(5));
            $user->addPosudbe($posudbe);

            $entityManager->persist($posudbe);
            $entityManager->persist($gradja);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->flasher->addSuccess('Uspješno ste rezervirali knjigu!');
            } else{
                $this->flasher->addWarning("Već ste rezervirali $maxRezerviranih knjige!");
            }

        return $this->redirectToRoute('rezervirane_knjige_korisnika');
    }

    #[Route('/{id}', name: 'posudbe_show', methods: ['GET'])]
    public function show(Posudbe $posudbe): Response
    {
        return $this->render('posudbe/show.html.twig', [
            'posudbe' => $posudbe,
        ]);
    }

    #[Route('/{id}/edit', name: 'posudbe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Posudbe $posudbe): Response
    {
        $form = $this->createForm(PosudbeType::class, $posudbe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->flasher->addSuccess('Promjene uspješno pohranjene!');

            return $this->redirectToRoute('posudbe_index');
        }

        return $this->render('posudbe/edit.html.twig', [
            'posudbe' => $posudbe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'posudbe_delete', methods: ['POST'])]
    public function delete(Request $request, Posudbe $posudbe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$posudbe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($posudbe);
            $entityManager->flush();
            $this->flasher->addSuccess('Uspješno uklonjeo!');
        }

        return $this->redirectToRoute('posudbe_index');
    }

    /**
     * @throws Exception
     */
    #[Route('/produlji-rezervaciju/{id}', name: 'rezervacija_extend', methods: ['GET'])]
    public function extension($id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $user Korisnici
         */
        $user = $this->getUser();

        $daniRezervacije = $user->getKnjiznice()->getDaniRezervacije();
        if($user->getBrojIskazniceKorisnika() != $rezervacija->getKorisnici()->getBrojIskazniceKorisnika()){
            $this->flasher->addError('Knjiga nije zadužena na vas!');
        }
        if($rezervacija->getDatumPosudbe()->diff($rezervacija->getDatumRokaVracanja())->format('%r%a') <
            ($daniRezervacije * 2) && $rezervacija->getStatus()->getId() == 5){

            $duration = "P".$daniRezervacije."D";
            $newDate = clone $rezervacija->getDatumRokaVracanja();
            $newDate->add(new DateInterval($duration));

            $rezervacija->setDatumRokaVracanja($newDate);
            $entityManager->persist($rezervacija);
            $entityManager->flush();

            $this->flasher->addSuccess('Uspješno ste produžili rezervaciju!');
        } else{
            $this->flasher->addWarning("Rezervaciju ste već jednom produžili!");
        }

        return $this->redirectToRoute('rezervirane_knjige_korisnika');

    }

    #[Route('/zatrazi-produljenje/{id}', name: 'zatrazi_produljenje_posudbe', methods: ['GET'])]
    public function zatraziProduljenjePosudbe($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $rezervacija = $entityManager->getRepository(Posudbe::class)->find($id);

        /**
         * @var $user Korisnici
         */
        $user = $this->getUser();
        if($user->getBrojIskazniceKorisnika() != $rezervacija->getKorisnici()->getBrojIskazniceKorisnika()){
            $this->flasher->addError('Knjiga nije zadužena na vas!');
        }
        else if ($rezervacija->getStatus()->getId() == 3) {

            $rezervacija->setStatus($entityManager->getRepository(Statusi::class)->find(9));
            $entityManager->flush();

            $this->flasher->addSuccess('Zahtjev za produljenje posudbe uspješno poslan!');
        }
        else{
            $this->flasher->addWarning('Vaš zahtjev za produljenje posudbe još uvijek čeka odluku knjižničara!');
        }
        return $this->redirectToRoute('posudjene_knjige_korisnika');
    }

}