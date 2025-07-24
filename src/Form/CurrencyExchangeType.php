<?php

namespace App\Form;

use App\Entity\Currency;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyExchangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', MoneyType::class, [
                'label' => 'Kwota',
                'currency' => false,
                'divisor' => 1,
            ])
            ->add('currency', EntityType::class, [
                'class' => Currency::class,
                'choice_label' => fn (Currency $c) => sprintf('%s (%s)', $c->getName(), $c->getCode()),
                'label' => 'Waluta docelowa',
            ])
            ->add('submit', SubmitType::class, [
            'label' => 'Przelicz',
            'attr' => ['class' => 'btn btn-primary mt-3']
            ]);
    }
}

