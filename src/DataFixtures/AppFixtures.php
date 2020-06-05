<?php

namespace App\DataFixtures;

use App\Entity\ForumDiscussionStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /* ForumDiscussionStatus */
        $forumDiscussionStatus = new ForumDiscussionStatus();
        $forumDiscussionStatus->setStatus('ouvert'); //id = 1
        $forumDiscussionStatus->setInfo('Ouvert à la discussion');
        $forumDiscussionStatus->setColor('success');
        $manager->persist($forumDiscussionStatus);

        $forumDiscussionStatus = new ForumDiscussionStatus();
        $forumDiscussionStatus->setStatus('fermer'); //id = 2
        $forumDiscussionStatus->setInfo('Fermé à la discussion');
        $forumDiscussionStatus->setColor('danger');
        $manager->persist($forumDiscussionStatus);

        $forumDiscussionStatus = new ForumDiscussionStatus();
        $forumDiscussionStatus->setStatus('accepter'); //id = 3
        $forumDiscussionStatus->setInfo('Candidature acceptée');
        $forumDiscussionStatus->setColor('success');
        $manager->persist($forumDiscussionStatus);

        $forumDiscussionStatus = new ForumDiscussionStatus();
        $forumDiscussionStatus->setStatus('refuser'); //id = 4
        $forumDiscussionStatus->setInfo('Candidature refusée');
        $forumDiscussionStatus->setColor('danger');
        $manager->persist($forumDiscussionStatus);

        $forumDiscussionStatus = new ForumDiscussionStatus();
        $forumDiscussionStatus->setStatus('en_attente'); //id = 5
        $forumDiscussionStatus->setInfo('Candidature en attente de réponses');
        $forumDiscussionStatus->setColor('warning');
        $manager->persist($forumDiscussionStatus);

        $manager->flush();
    }
}
