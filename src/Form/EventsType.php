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

    public function __construct(TranslatorInterface $translator)
    {
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
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('form.cost')]
            ])
            ->add('date', null, [
                'label' => $this->translator->trans('form.date'),
                'widget' => 'single_text',
            ])
            ->add('duration', null, [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('form.duration')]
            ])
            ->add('terrain_type', null, [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('form.terrain_type')]
            ])
            ->add('weather', null, [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('form.weather')]
            ])
            ->add('temperature', null, [
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('form.temperature')]
            ])
            ->add('beginner_friendly', null, [
                'label' => $this->translator->trans('form.beginner_friendly'),
            ])
            ->add('equipement_rental', null, [
                'label' => $this->translator->trans('form.equipement_rental'),
            ])
            ->add('save', SubmitType::class, [
                'label' => $this->translator->trans('form.save')
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
