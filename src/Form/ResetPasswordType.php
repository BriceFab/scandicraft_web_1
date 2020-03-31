<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'input.email',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'input.not_blank',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'input.min_length',
                        'max' => 256,
                    ]),
                ],
                'disabled' => true,
            ])
            ->add('username', TextType::class, [
                'label' => 'Pseudonyme',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'input.not_blank',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'input.min_length',
                        'max' => 20,
                    ]),
                ],
                'disabled' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'mapped' => false,
                'first_options'  => ['label' => 'input.password', 'error_bubbling' => true],
                'second_options' => ['label' => 'input.password.repeated', 'error_bubbling' => true],
                'invalid_message' => 'The password fields must match.',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 256,
                    ]),
                ],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
