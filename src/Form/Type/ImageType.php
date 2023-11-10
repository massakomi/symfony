<?php

// src/Form/Type/ShippingType.php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        /*$resolver->setDefaults([
            'choices' => [
                'Standard Shipping' => 'standard',
                'Expedited Shipping' => 'expedited',
                'Priority Shipping' => 'priority',
            ],
        ]);*/
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['path'] = $form->getData();
    }

    /*public function finishView(FormView $view, FormInterface $form, array $options): string
    {
;
    }*/

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}