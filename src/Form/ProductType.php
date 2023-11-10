<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\Type\ImageType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Заголовок',
                'attr' => [
                    'placeholder' => 'Введите текст'
                ]
            ])
            ->add('price', null, [
                'required' => false,
                'label' => 'Цена'
            ])
            ->add('price_old', null, [
                'required' => false,
                'label' => 'Цена без скидки'
            ])
            ->add('slug', null, [
                'label' => 'Символьный код'
            ])
            /*->add('imagexx', ImageType::class, [
                'mapped' => false,
            ])*/
            ->add('category_id', EntityType::class, [
                'required' => false,
                'class' => Category::class,
                'choice_label' => 'title',
                'placeholder' => 'Выбрать',
                'label' => 'Категория',
                'choice_value' => function ($entity): string {
                    return is_object($entity) ? $entity->getId() : $entity;
                },
            ])
            ->add('detail_text', null, [
                'label' => 'Описание полное'
            ])
            ->add('imagefile', FileType::class, [
                'label' => 'Картинка',
                'mapped' => false,
                'required' => false,
                // для кастомных полей можно завести свою валидацию на месте
                'constraints' => [
                    new File([
                        //'maxSize' => '1024k',
                        /*'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],*/
                        //'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
                'label' => 'Активность',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить',
                'attr' => [
                    'class' => ' btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
        //$resolver->setAllowedTypes('path', 'bool');
    }
}
