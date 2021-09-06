<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\hiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Controller\MovieController;

class GenreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
      ->add('Action', CheckboxType::class, [
          'label'    => 'Action',
          'value' => '28',
          'required' => false,
      ])
      ->add('Aventure', CheckboxType::class, [
          'label'    => 'Aventure',
          'value' => '12',
          'required' => false,
      ])
      ->add('Animation', CheckboxType::class, [
          'label'    => 'Animation',
          'value' => '16',
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
