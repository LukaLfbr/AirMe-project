<?php

namespace App\Form;

use App\Entity\CarPoolingOffer;
use App\Entity\Events;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Doctrine\DBAL\Types\TimeType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

class CarPoolingOfferType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class , [
                'label' => false,
                'attr' => [
                    'placeholder' => 'carpooling.name'
                ]
            ])
            ->add('description', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'carpooling.description'
                ]
            ])
            ->add('departure_location', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'carpooling.departure_location'
                ]
            ])
            ->add('arrival_location', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'carpooling.arrival_location'
                ]
            ])
            ->add('departure_time', TimeType::class, [
                'label' => false,
                'widget' => 'choice',
                'input' => 'datetime',
                'with_seconds' => false,
                'attr' => [
                    'placeholder' => 'carpooling.departure_time'
                ]
            ])
            ->add('seats_available', IntegerType::class, [
                'false' => false,
                'inputmode' => 'numeric',
                'style' => 'appareance: textfield',
                'attr' => [
                    'placeholder' => 'carpooling.seats_available',
                    'min' => 1,
                    'max' => 10,
                ],
                'constraints' => [
                    new Assert\GreaterThan([
                        'value' => 1,
                        'message' => 'carpooling.error.min'
                    ])
                ],
                'constraits' => [
                    new Assert\LessThan([
                        'value' => 10,
                        'message' => 'carpooling.error.max'
                    ])
                ]
            ])
            ->add('creator', EntityType::class, [   
                'class' => User::class,'choice_label' => 'id',
            ])
            ->add('event', EntityType::class, [
                'class' => Events::class,'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CarPoolingOffer::class,
        ]);
    }
}
