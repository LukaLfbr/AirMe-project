<?php

namespace App\Form;

use App\Entity\CarPoolingOffer;
use App\Entity\Events;
use App\Entity\User;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Contracts\Translation\TranslatorInterface;

class CarPoolingOfferType extends AbstractType
{
	private TranslatorInterface $translator;

	public function __construct(TranslatorInterface $translator) {
		$this->translator = $translator;
	}

	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('name', TextType::class, [
				'label' => false,
				'attr' => [
					'placeholder' => $this->translator->trans('carpooling.name')
				]
			])
			->add('description', TextareaType::class, [
				'label' => false,
				'attr' => [
					'placeholder' => $this->translator->trans('carpooling.description')
				]
			])
			->add('departure_location', TextType::class, [
				'label' => false,
				'attr' => [
					'placeholder' => $this->translator->trans('carpooling.offer.departure_location_input')
				]
			])
			->add('arrival_location', TextType::class, [
				'label' => false,
				'attr' => [
					'placeholder' => $this->translator->trans('carpooling.offer.arrival_location_input')
				]
			])
			->add('departure_time', TextType::class, [
				'label' => false,
				'attr' => [
					'placeholder' => $this->translator->trans('carpooling.offer.departure_time_input')
				]
			])
			->add('seats_available', IntegerType::class, [
				'label' => false,
				'required' => false,
				'attr' => [
					'placeholder' => $this->translator->trans('carpooling.offer.seats_available_input'),
					'min' => 1,
					'max' => 10,
				],
				'constraints' => [
					new Assert\GreaterThan([
						'value' => 1,
						'message' => $this->translator->trans('carpooling.error.min')
					]),
					new Assert\LessThan([
						'value' => 10,
						'message' => $this->translator->trans('carpooling.error.max')
					])
				]
			])
			->add('event', EntityType::class, [
				'class' => Events::class,
				'choice_label' => 'name',   
				'label' => false,
				'autocomplete' => true,
				'placeholder' => 'Sélectionnez un événement',
				'attr' => ['class' => 'select2-autocomplete'], 
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => CarPoolingOffer::class,
		]);
	}
}

