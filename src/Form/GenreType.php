<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class GenreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
      ->add('action', CheckboxType::class, [
          'label'    => 'Action',
          'required' => false,
      ])
      ->add('aventure', CheckboxType::class, [
          'label'    => 'Aventure',
          'required' => false,
      ])
      ->add('save', SubmitType::class, [
        'attr' => ['class' => 'save'],
      ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
