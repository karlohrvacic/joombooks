<?php

namespace App\Form;

use App\Entity\Korisnici;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KorisniciType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ime')
            ->add('prezime')
            ->add('email')
            ->add('lozinka')
            ->add('brojTelefona')
            ->add('fotografija')
            ->add('razred')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Korisnici::class,
        ]);
    }
}
