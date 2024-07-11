<?php

namespace App\Form;

use App\Entity\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de l\'événement *',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('description', null, [
                'label' => 'Description de l\'événement *',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('location', null, [
                'label' => 'Lieu de l\'événement *',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('paf', null, [
                'label' => 'PAF',
            ])
            ->add('date', null, [
                'label' => 'Date de l\'événement et heure *',
                'widget' => 'single_text',
            ])
            ->add('duration', null, [
                'label' => 'Durée de l\'événement',
            ])
            ->add('terrain_type', null, [
                'label' => 'Type de terrain',
            ])
            ->add('weather', null, [
                'label' => 'Conditions météo',
            ])
            ->add('temperature', null, [
                'label' => 'Température',
            ])
            ->add('beginner_friendly', null, [
                'label' => 'Convient aux débutants',
            ])
            ->add('equipement_rental', null, [
                'label' => 'Location d\'équipement',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Sauvegarder un événement',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
