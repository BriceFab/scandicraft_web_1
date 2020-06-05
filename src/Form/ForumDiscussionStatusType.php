<?php

namespace App\Form;

use App\Entity\ForumDiscussion;
use App\Entity\ForumDiscussionStatus;
use App\Repository\ForumDiscussionStatusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumDiscussionStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var ForumDiscussion $entity */
                $entity = $event->getData();
                $form = $event->getForm();
                $forumDiscussion = $form->getParent()->getData();

                $query_builder = null;
                if ($forumDiscussion != null) { // && $forumDiscussion->getSubCategory()->getAcceptStaffOnly()
                    $query_builder = function (ForumDiscussionStatusRepository $repo) use ($forumDiscussion) {
                        return $repo->createStatusQueryBuilder($forumDiscussion);
                    };
                }

                $form->add('status', EntityType::class, [
                    'label' => false,
                    'required' => true, //false
                    'class' => ForumDiscussionStatus::class,
                    'query_builder' => $query_builder,
                    'help' => 'Attention, si vous fermer la discussion, vous ne pourrez plus la réouvrir.'
                ]);
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ForumDiscussionStatus::class,
        ]);
    }
}
