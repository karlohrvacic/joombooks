<?php

namespace App\Form;

use App\Entity\Autori;
use App\Entity\Drzave;
use App\Entity\Zanrovi;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutoriType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ime')
            ->add('prezime')
            //->add('popisGradje')
            ->add('drzava', EntityType::class, [
                'required' => false,
                'class' => Drzave::class,
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
