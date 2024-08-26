<?php

namespace App\Form;

use App\Entity\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class EventsType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator) {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $notBlankMessage = $this->translator->trans('form.field.empty');

        $builder
            ->add('name', null, [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('form.field.name')],
                'constraints' => [
                    new NotBlank([
                        'message' => $notBlankMessage,
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('form.field.description'),
                    'class' => 'tinymce',
                ],
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => $notBlankMessage,
                    ]),
                ],
            ])
            ->add('location', null, [
                'attr' => ['placeholder' => $this->translator->trans('form.field.location')],
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => $notBlankMessage,
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
