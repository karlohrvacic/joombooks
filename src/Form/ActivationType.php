<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


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
            ->add('code', null , [
                'label' => 'Upišite kod iz e-maila:',
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Lozinke se moraju podudarati.',
                'first_options' => ['label' => 'Nova lozinka'],
                'second_options' => ['label' => 'Ponovite novu lozinku'],
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
