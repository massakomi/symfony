<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderStatus;
use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\Shipment;
use App\Entity\User;
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

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_id', EntityType::class, [
                //'required' => false,
                'class' => User::class,
                'choice_label' => 'username',
                //'placeholder' => 'Выбрать',
                'label' => 'Пользователь',
                'choice_value' => function ($entity): string {
                    return is_object($entity) ? $entity->getId() : "$entity";
                },
            ])
            ->add('status', EntityType::class, [
                //'required' => false,
                'class' => OrderStatus::class,
                'choice_label' => 'name',
                //'placeholder' => 'Выбрать',
                'label' => 'Статус заказа',
                'choice_value' => function ($entity): string {
                    return is_object($entity) ? $entity->getId() : "$entity";
                },
            ])
            ->add('payment_id', EntityType::class, [
                //'required' => false,
                'class' => Payment::class,
                'choice_label' => 'name',
                //'placeholder' => 'Выбрать',
                'label' => 'Способ оплаты',
                'choice_value' => function ($entity): string {
                    return is_object($entity) ? $entity->getId() : "$entity";
                },
            ])
            ->add('shipment_id', EntityType::class, [
                //'required' => false,
                'class' => Shipment::class,
                'choice_label' => 'name',
                //'placeholder' => 'Выбрать',
                'label' => 'Способ доставки',
                'choice_value' => function ($entity): string {
                    return is_object($entity) ? $entity->getId() : "$entity";
                },
            ])
            ->add('address', null, [
                'required' => false,
                'label' => 'Адрес доставки',
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
            'data_class' => Order::class,
        ]);
    }
}
