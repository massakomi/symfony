<?php
// src/Form/Type/TaskType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // label required это для html части формы, сервер не проверяет
            ->add('task', TextType::class, [
                //'label' => 'To Be Completed Before',
                'required' => false,
            ])
            ->add('dueDate', DateType::class, [
                'required' => $options['require_due_date'],
            ])
            // угадываемый тип поля
            ->add('x')
            ->add('createDate')
            // неотображаемое поле (не выходит ошибка отсутствия поля в объекте)
            ->add('agreeTerms', CheckboxType::class, ['mapped' => false])
            // кнопка
            ->add('save', SubmitType::class, [
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // ...,
            'require_due_date' => false,
        ]);

        // вы также можете определить позволенные типы, значения и
        // любые другие функции, поддерживающиеся компонентом OptionsResolver
        $resolver->setAllowedTypes('require_due_date', 'bool');
    }
}