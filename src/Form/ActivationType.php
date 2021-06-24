<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class ActivationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password',
                        'class' => 'form-control'
                    ],
                    'row_attr' => [
                        'class' => 'knjiznica-label'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Molimo unesite lozinku',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Vaša lozinka mora imati barem {{ limit }} znakova',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Lozinka',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password',
                        'class' => 'form-control'],
                    'label' => 'Ponovite Lozinku',
                    'row_attr' => [
                        'class' => 'knjiznica-label'
                    ],
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,


                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
