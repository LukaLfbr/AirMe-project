<?php

namespace App\Form;

use App\Entity\Events;
use App\Repository\EventsRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class EventsAutocompleteField extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Events::class,
            // 'choice_label' => 'name',

            // choose which fields to use in the search
            // if not passed, *all* fields are used
            'searchable_fields' => ['name'],

            // 'security' => 'ROLE_USER',
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
