<?php

namespace App\Form;

use App\Entity\ForumDiscussion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ForumDiscussionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'input.not_blank',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'input.min_length',
                        'max' => 140,
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'input.not_blank',
                    ]),
                    new Length([
                        'min' => 25,
                        'minMessage' => 'input.min_length',
                        // 'max' => 500,
                    ]),
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var ForumDiscussion $entity */
                $entity = $event->getData();
                $form = $event->getForm();

                if ($entity->getSubCategory()->getAcceptStaffOnly()) {
                    $form->add('staff_only', CheckboxType::class, [
                        'label' => 'Visible seulement pour le staff ?',
                        'required' => false,
                    ]);
                }

                // if ($entity->getId() != null && !$entity->getSubCategory()->getAcceptStaffOnly()) { //= edit mode
                //     $form->add('status', ForumDiscussionStatusType::class);
                // }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumDiscussion::class,
        ]);
    }
}
