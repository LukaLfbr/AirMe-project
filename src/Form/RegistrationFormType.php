<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Constraints\Type;

class RegistrationFormType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new EmailConstraint(['message' => 'form.email.invalid']),
                    new Length([
                        'max' => 36,
                        'maxMessage' => 'form.email.toolong',
                    ]),
                ],
                'label' => false,
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'form.password.blank']),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'validators.password.too.short',
                        'max' => 50,
                    ]),
                ],
                'label' => false,
            ])
            ->add('phoneNumber', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'form.phone_number.label'
                ],
                'constraints' => [
                    new Length([
                        'max' => 12,
                        'min' => 8,
                        'maxMessage' => $this->translator->trans('form.phone.too.long'),
                        'minMessage' => $this->translator->trans('form.phone.too.short'),
                    ]),
                    new Type([
                        'type' => 'integer',
                        'message' => $this->translator->trans('form.phone.must_be_integer'),
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
