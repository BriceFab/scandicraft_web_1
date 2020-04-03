<?php

namespace App\Form;

use App\Entity\User;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
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
                    new Regex([
                        'pattern' => "/^[a-zA-Z_\d]+$/",
                        'message' => 'Votre pseudonyme doit contenir uniquement des lettres, chiffres et underscore (_)'
                    ])
                ],
                'trim' => true,
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
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'register',
            ]);
        // ->add('agreeTerms', CheckboxType::class, [
        //     'mapped' => false,
        //     'constraints' => [
        //         new IsTrue([
        //             'message' => 'You should agree to our terms.',
        //         ]),
        //     ],
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
