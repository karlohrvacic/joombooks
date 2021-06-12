<?php

namespace App\Controller;

use App\Entity\Knjiznice;
use App\Entity\Korisnici;
use App\Form\ActivationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ActivateController extends AbstractController
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private $session;

    public function __construct(SessionInterface $session, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->session = $session;
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/aktivacija/{code}', name: 'activation_index')]
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, string $code = null): Response
    {
        

        
        if ($code) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->session->set('code', $code);
            
            return $this->redirectToRoute('activation_index');
        }


        /** @var $user Korisnici */
        $user = $this->getUser();
        if($user instanceof Korisnici){
            $this->addFlash('alert', 'Ne možete aktivirati račun dok ste prijavljeni, prvo se odjavite!');
            return $this->redirectToRoute('korisnicki_izbornik');
        }

        /** @var $user Knjiznice */
        $user = $this->getUser();
        if($user instanceof Knjiznice){
            $this->addFlash('alert', 'Ne možete aktivirati račun dok ste prijavljeni, prvo se odjavite!');
            return $this->redirectToRoute('knjiznica_izbornik');
        }

        $code = $this->session->get('code');
        if (null === $code) {
            throw $this->createNotFoundException('Nema tokena u URL-u ili u sesiji.');
        }

        $form = $this->createForm(ActivationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var $korisnik Korisnici
             */
            $korisnik = $this->getDoctrine()
                ->getRepository(Korisnici::class)
                ->findOneBy([
                    'email' => $form->get('email')->getData(),
                ]);

            if (!$korisnik) {
                    throw $this->createNotFoundException(
                        'Nema pronađenog korisnika!'
                    );
            } elseif (($this->passwordEncoder->isPasswordValid($korisnik, $form->get('password')->getData()))){
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

            return $this->redirectToRoute('app_login');
        }

        return $this->render('activate/activate.html.twig', [
            'form' => $form->createView(),
        ]);

    }
    
}