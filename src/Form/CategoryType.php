<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label' => 'Главное изображение',
                'required' => false,
                'mapped' => false
            ])
            ->add('title', TextType::class, [
                'label' => 'Заголовок категории',
                'attr' => [
                    'placeholder' => 'Введите текст'
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Описание категории',
                'attr' => [
                    'placeholder' => 'Введите текст'
                ]
            ])
            ->add('is_published', CheckboxType::class, [
                'required' => false
            ])
            ->add('sort', NumberType::class, [
                'required' => false,
            ])
            ->add('parent', NumberType::class, [
                'required' => false,
            ])
            ->add('create_at', DateTimeType::class, [
                'disabled' => true
            ])
            ->add('update_at', DateTimeType::class, [
                'disabled' => true
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
                'attr' => [
                    'class' => ' btn btn-success'
                ]
            ])
            ->add('delete', SubmitType::class, [
                'label' => 'Удалить',
                'attr' => [
                    'class' => ' btn btn-danger'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
