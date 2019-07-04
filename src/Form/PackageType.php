<?php

namespace App\Form;

use App\Document\Requirement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('namespace', TextType::class)
            ->add('version', TextType::class)
            ->add('status', IntegerType::class)
            ->add('created', DateTimeType::class, ['required' => false, 'mapped' => false])
            ->add('updated', DateTimeType::class, ['required' => false, 'mapped' => false])
            ->add('touched', DateTimeType::class, ['required' => false, 'mapped' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Requirement::class,
        ]);
    }
}
