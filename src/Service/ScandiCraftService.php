<?php

namespace App\Service;

use App\Entity\ForumDiscussion;
use App\Entity\MySocialmedia;
use App\Entity\Survey;
use App\Repository\ForumDiscussionStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ScandiCraftService
{
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function getSocialmedia()
    {
        return $this->em->getRepository(MySocialmedia::class)->getSocialmedia();
    }

    public function countTotalSurvey()
    {
        $all_survey = $this->em->getRepository(Survey::class)->findAll();
        $enabled_sondages = array_filter($all_survey, function ($val) {
            return $val->isEnable();
        });
        if (!$this->security->getUser()) {
            return count($enabled_sondages);
        } else {
            $nbr_survey_not_answer = $this->countSurveysNotAnswer($all_survey);
            return $nbr_survey_not_answer;
        }
    }

    public function countSurveysNotAnswer($surveys)
    {
        $count = 0;
        if (!$this->security->getUser()) return 0;
        foreach ($surveys as $value) {
            if ($value->countUserAnswers($this->security->getUser()->getId()) == 0) {
                $count++;
            }
        }
        return $count;
    }

    public function generateSlug($string)
    {
        $slug = \Transliterator::createFromRules(
            ':: Any-Latin;'
                . ':: NFD;'
                . ':: [:Nonspacing Mark:] Remove;'
                . ':: NFC;'
                . ':: [:Punctuation:] Remove;'
                . ':: Lower();'
                . '[:Separator:] > \'-\''
        )->transliterate($string);

        return $slug;
    }

    public function removeBalises($string)
    {
        $string = htmlspecialchars_decode($string, ENT_HTML5);       //encode en caractères normaux
        $string = preg_replace("/<[^>]*>/", "", $string); //enlève les balises
        return $string;
    }

    /*
        Check si l'utilisateur peut effectuer une action en fonction du status de la discussion
    */
    public function checkDiscussionActionFromStatus(ForumDiscussion $discussion)
    {
        if ($discussion->getStatus() == null) {
            return true;
        }

        switch ($discussion->getStatus()->getId()) {
            case ForumDiscussionStatusRepository::OUVERT_ID: //ouvert
                return true;
            case ForumDiscussionStatusRepository::FERMER_ID: //fermer
                return false;
            case ForumDiscussionStatusRepository::ACCEPTER_ID: //accepter
                return false;
            case ForumDiscussionStatusRepository::REFUSER_ID: //refuser
                return false;
            case ForumDiscussionStatusRepository::EN_ATTENTE_ID: //en attente
                return true;
            default:
                return true;
        }
    }
}
