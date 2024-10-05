<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom / prénom :',
                'required' => true,
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Date de naissance :',
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'required' => true,
            ])
            ->add('dateOfDeath', DateType::class, [
                'label' =>  'Date du décès :',
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('nationality', ChoiceType::class, [
                'label' => 'Nationalité :',
                'choices' => array_flip(Countries::getNames()),
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
