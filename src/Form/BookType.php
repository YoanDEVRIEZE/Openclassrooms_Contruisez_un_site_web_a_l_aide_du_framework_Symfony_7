<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Editor;
use App\Enum\BookStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre :',
                'required' => true,
            ])
            ->add('isbn', TextType::class, [
                'label' => 'Isbn :',
                'required' => true,
            ])
            ->add('cover', TextType::class, [
                'label' => 'Couverture :',
                'required' => true,
            ])
            ->add('editAt', DateType::class, [
                'label' => 'Date d\'édition :',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'required' => true,
            ])
            ->add('plot', TextType::class, [
                'label' => 'Plot :',
                'required' => true,
            ])
            ->add('pageNumber', IntegerType::class, [
                'label' => 'Nombre de page :',
                'required' => true,
            ])
            ->add('status', EnumType::class, [
                'label' => 'Status :',
                'class' => BookStatus::class,
            ])
            ->add('editor', EntityType::class, [
                'label' => 'Editeur :',
                'class' => Editor::class,
                'choice_label' => 'name',
            ])
            ->add('author', EntityType::class, [
                'label' => 'Auteur :',
                'class' => Author::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
