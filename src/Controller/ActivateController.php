<?php

namespace App\Controller;

use App\Entity\Korisnici;
use App\Form\ActivationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ActivateController extends AbstractController
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/aktivacija/{code}', name: 'activation_index', defaults: ['code' => ''], methods: ['GET', 'POST'])]
    public function index(Request $request, $code): Response
    {
        $form = $this->createForm(ActivationType::class, array(
            'code' => $code,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $korisnik = $this->getDoctrine()
                ->getRepository(Korisnici::class)
                ->findOneBy([
                    'email' => $form->get('email')->getData(),
                    'lozinka' => $form->get('code')->getData()
                ]);

            if (!$korisnik) {
                throw $this->createNotFoundException(
                    'Nema pronađenog korisnika!'
                );
            }

            $hash = $this->passwordEncoder->encodePassword($korisnik, $form->get('password')->getData());

            $korisnik->setLozinka($hash);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($korisnik);
            $entityManager->flush();

            $this->addFlash('success', 'Uspješno ste aktivirali svoj račun!');
            $this->addFlash('success', 'Molimo izvršite prvu prijavu');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('activate/activate.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}