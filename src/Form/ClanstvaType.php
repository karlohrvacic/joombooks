<?php

namespace App\Form;

use App\Entity\Clanstva;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClanstvaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brojIskazniceKorisnika')
            ->add('korisnik')
            ->add('knjiznica')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Clanstva::class,
        ]);
    }
}
