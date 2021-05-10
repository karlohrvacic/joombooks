<?php

namespace App\Form;

use App\Entity\Korisnici;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KorisniciType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ime', TextType::class, [
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                        'class' => 'form-control'
            ]])
            ->add('prezime', TextType::class, [
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('email', EmailType::class, [
                'row_attr' => [
                        'class' => 'knjiznica-label'
                ],
                'attr' => [
                        'class' => 'form-control'
                ]
            ])


            /*->add('lozinka', PasswordType::class, [
                'required' => false,
                'empty_data' => ''
            ])*/
                ->add('brojIskazniceKorisnika', NumberType::class,[
                    'row_attr' => [
                        'class' => 'knjiznica-label'
                    ],
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]

            )
            ->add('brojTelefona', NumberType::class, [
                'required'   => false,
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
                ])
            ->add('fotografija', FileType::class, [
                'mapped' => false,
                'required' => false,
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('razred', TextType::class,[
                'row_attr' => [
        'class' => 'knjiznica-label'
    ],
                'attr' => [
        'class' => 'form-control'
    ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Korisnici::class,
        ]);
    }
}
