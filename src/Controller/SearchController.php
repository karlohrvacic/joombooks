<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function index(): Response
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    public function searchBarForGradja(){
        $form = $this->createFormBuilder()
            ->setAction($this->generateurl('handle_Search_Bar_For_Gradja'))
            ->add('query', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'search',
                    'placeholder' => 'naslov knjige; ime autora',
                    'aria-label' => 'Search'
                ]
            ])
            ->add('search', SubmitType::class, [
                'attr' => [
                    'class' => 'search-btn'
                ]
            ])
            ->getForm();
        return $this->render('search/searchBar.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
