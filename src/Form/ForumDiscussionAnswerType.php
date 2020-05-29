<?php

namespace App\Form;

use App\Entity\ForumDiscussionAnswer;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForumDiscussionAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextType::class, [
                'label' => 'Message',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'input.not_blank',
                    ]),
                    new Length([
                        'min' => 25,
                        'minMessage' => 'input.min_length',
                        'max' => 500,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumDiscussionAnswer::class,
        ]);
    }
}
