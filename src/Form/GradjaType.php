<?php

namespace App\Form;

use App\Entity\Gradja;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GradjaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ISBN', StringType::class,[
                'required'   => false,
            ])
            ->add('naslov')
            //->add('oibKnjiznice') //TODO
            ->add('fotografija', FileType::class, [ //TODO
                'required' => false,
            ])
            ->add('opis',StringType::class, [
                'required' => false,
            ])
            ->add('datumDodavanja', DateType::class)
            ->add('godinaIzdanja', DateType::class, [
                    'required' => false,
                ])
            ->add('jezik',StringType::class, [
                'required' => false,
            ])
            ->add('brojInventara', NumberType::class)
            ->add('autori', AutoriType::class, [
                'required' => false,
            ])
            ->add('status')
            ->add('zanrovi', ZanroviType::class, [
                'required' => false,
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
