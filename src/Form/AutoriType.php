<?php

namespace App\Form;

use App\Entity\Autori;
use App\Entity\Drzave;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutoriType extends AbstractType
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
                ]
            ])
            ->add('prezime', TextType::class, [
                'row_attr' => [
                    'class' => 'knjiznica-label'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            //->add('popisGradje')
            ->add('drzava', EntityType::class, [
                'required' => false,
                'class' => Drzave::class,
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
            'data_class' => Autori::class,
        ]);
    }
}
