<?php

namespace App\Form;

use App\Entity\Autori;
use App\Entity\Gradja;
use App\Entity\Izdavaci;
use App\Entity\Jezici;
use App\Entity\Statusi;
use App\Entity\Zanrovi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GradjaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ISBN', TextType::class,[
                'required' => false,
                'label' => 'ISBN',
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('naslov', TextType::class, [
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
            ->add('opis',TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('datumDodavanja', DateType::class, [
                'data' => new \DateTime("now"),
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('godinaIzdanja', DateType::class, [
                'widget' => 'choice',
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('jezici',EntityType::class, [
                'required' => false,
                'class' => Jezici::class,
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('brojInventara', NumberType::class, [
                    'row_attr' => [
                        'class' => 'knjiznica-label'
                    ],
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]
            )
            ->add('autori', EntityType::class, [
                'multiple' => true,
                'class' => Autori::class,
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('status', EntityType::class, [
                'class' => Statusi::class,
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('zanrovi', EntityType::class, [
                'multiple' => true,
                'class' => Zanrovi::class,
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('izdavac',EntityType::class, [
                'required' => false,
                'class' => Izdavaci::class,
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
           // ->add('knjiznicaVlasnik')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gradja::class,
        ]);
    }
}
