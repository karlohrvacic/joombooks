<?php

namespace App\Form;

use App\Entity\Korisnici;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KorisniciType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ime')
            ->add('prezime')
            ->add('email', EmailType::class)
            /*->add('lozinka', PasswordType::class, [
                'required' => false,
                'empty_data' => ''
            ])*/
                ->add('brojIskazniceKorisnika', NumberType::class)
            ->add('brojTelefona', NumberType::class, [
                'required'   => false,
                ])
            ->add('fotografija', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('razred', NumberType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Korisnici::class,
        ]);
    }
}
