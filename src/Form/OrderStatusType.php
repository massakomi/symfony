<?php

namespace App\Form;

use App\Entity\OrderStatus;
use App\Entity\Payment;
use App\Entity\Shipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderStatusType extends AbstractType
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
            ->add('code', null, [
                'label' => 'Код',
                'attr' => [
                    'placeholder' => 'Введите текст'
                ]
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
            'data_class' => OrderStatus::class,
        ]);
    }
}
