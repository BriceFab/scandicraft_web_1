<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Entity\SurveyAnswers;
use App\Repository\SurveyAnswersRepository;
use App\Repository\SurveyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SurveyController extends AbstractController
{
    /**
     * @Route("/sondages", name="sondages")
     */
    public function showSondages(Request $request, PaginatorInterface $paginator, SurveyRepository $surveyRepository)
    {
        $data = $surveyRepository->findBy([], ['fromTheDate' => 'ASC']);
        $enabled_sondages = array_filter($data, function ($val) {
            return $val->isEnable();
        });

        $archived_sondages = array_filter($data, function ($val) {
            return !$val->isEnable();
        });

        $sondages = $paginator->paginate(
            $enabled_sondages,
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        $nbr_not_answer = $this->countSurveysNotAnswer($data);

        return $this->render('survey/list.html.twig', [
            'enabled_sondages' => $sondages,
            'archived_sondages' => $archived_sondages,
            'nbr_not_answer' => $nbr_not_answer,
            'total_enabled_sondages' => count($enabled_sondages),
        ]);
    }

    /**
     * @Route("/post/survey_answer", name="post_survey_answer", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function postSurveyAnswer(Request $request, EntityManagerInterface $em, SurveyRepository $surveyRepository, SurveyAnswersRepository $surveyAnswersRepository)
    {
        try {
            //check survey
            $survey_id = $request->get('survey');
            $survey = $surveyRepository->findOneBy(['id' => $survey_id]);
            if (!$survey->isEnable()) {
                $this->addFlash('error', 'Le sondage a expiré !');
                return $this->redirectToRoute('sondages');
            }

            //check answer
            $answer_id = $request->get('answer');
            $answer_list = $this->getAnswerList($survey, $answer_id);
            if (!$answer_list) {
                $this->addFlash('error', 'Cette réponse n\'existe pas !');
                return $this->redirectToRoute('sondages');
            }

            //check if has already answer
            $user_nbr_answers = $survey->countUserAnswers($this->getUser()->getId());
            if ($user_nbr_answers > 0) {
                $this->addFlash('error', 'Vous avez déjà répondu à ce sondage..');
                return $this->redirectToRoute('sondages');
            }

            $answer = new SurveyAnswers();
            $answer->setUser($this->getUser());
            $answer->setSurvey($survey);
            $answer->setAnswer($answer_list);

            $em->persist($answer);
            $em->flush();

            $this->addFlash('notice', 'Merci pour votre participation !');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue..');
        }

        return $this->redirectToRoute('sondages');
    }

    private function countSurveysNotAnswer($surveys)
    {
        $count = 0;
        if (!$this->getUser()) return 0;
        foreach ($surveys as $value) {
            if ($value->countUserAnswers($this->getUser()->getId()) == 0) {
                $count++;
            }
        }
        return $count;
    }

    private function getAnswerList($survey, $answer_id)
    {
        $answer_list = $survey->getAnswersList();
        foreach ($answer_list as $value) {
            if ($value->getId() == $answer_id) {
                return $value;
            }
        }
        return null;
    }
}
