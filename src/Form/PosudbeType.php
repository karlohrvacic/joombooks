<?php

namespace App\Form;

use App\Entity\Posudbe;
use Doctrine\DBAL\Types\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PosudbeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idGradje')
            ->add('brojIskazniceKorisnika')
            ->add('datumPosudbe')
            ->add('datumRokaVracanja')
            ->add('datumVracanja',DateType::class, [
                'required'   => false,
            ])
            ->add('gradja')
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Posudbe::class,
        ]);
    }
}
