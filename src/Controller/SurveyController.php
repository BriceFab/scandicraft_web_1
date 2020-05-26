<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Entity\SurveyAnswers;
use App\Entity\SurveyComments;
use App\Repository\SurveyCommentsRepository;
use App\Repository\SurveyRepository;
use App\Service\ScandiCraftService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SurveyController extends AbstractController
{
    /**
     * @Route("/sondages", name="sondages")
     */
    public function showSondages(Request $request, PaginatorInterface $paginator, SurveyRepository $surveyRepository, ScandiCraftService $service)
    {
        $data = $surveyRepository->findBy([], ['fromTheDate' => 'DESC']);
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

        $nbr_not_answer = $service->countSurveysNotAnswer($data);

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
    public function postSurveyAnswer(Request $request, EntityManagerInterface $em, SurveyRepository $surveyRepository)
    {
        $defaultRoute = 'sondages';

        try {
            //check survey
            $survey_id = $request->get('survey');
            $survey = $surveyRepository->findOneBy(['id' => $survey_id]);
            if (!$survey || !$survey->isEnable()) {
                $this->addFlash('error', 'Le sondage a expiré !');
                return $this->retirectToPreviousRoute($request, $defaultRoute);
            }

            //check answer
            $answer_id = $request->get('answer');
            $answer_list = $this->getAnswerList($survey, $answer_id);
            if (!$answer_list) {
                $this->addFlash('error', 'Cette réponse n\'existe pas !');
                return $this->retirectToPreviousRoute($request, $defaultRoute);
            }

            //check if has already answer
            $user_nbr_answers = $survey->countUserAnswers($this->getUser()->getId());
            if ($user_nbr_answers > 0) {
                $this->addFlash('error', 'Vous avez déjà répondu à ce sondage..');
                return $this->retirectToPreviousRoute($request, $defaultRoute);
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

        return $this->retirectToPreviousRoute($request, $defaultRoute);
    }

    /**
     * @Route("/sondage/{slug}", name="survey_comments")
     * @ParamConverter("survey")
     */
    public function showSondageComments(Survey $survey)
    {
        $has_answer = false;
        if ($this->getUser()) {
            $has_answer = $survey->countUserAnswers($this->getUser()->getId()) > 0;
        }

        if (!$survey->isEnable()) {
            $this->addFlash('error', 'Ce sondage n\'est pas actif, voici l\'archive.');
            return $this->redirectToRoute('survey_archived_comments', ['slug' => $survey->getSlug()]);
        }

        return $this->render('survey/show.html.twig', [
            'sondage' => $survey,
            'has_answer' => $has_answer
        ]);
    }

    /**
     * @Route("/sondage/archive/{slug}", name="survey_archived_comments")
     * @ParamConverter("survey")
     */
    public function showArchivedSondageComments(Survey $survey)
    {
        if ($survey->isEnable()) {
            $this->addFlash('error', 'Ce sondage n\'est pas archivé.');
            return $this->redirectToRoute('sondages');
        }

        return $this->render('survey/show_archived.html.twig', [
            'sondage' => $survey
        ]);
    }

    /**
     * @Route("/post/survey_comment", name="post_survey_comment", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function postSurveyComment(Request $request, EntityManagerInterface $em, SurveyRepository $surveyRepository, ValidatorInterface $validator)
    {
        $defaultRoute = 'sondages';

        try {
            //check survey
            $survey_id = $request->get('survey');
            $survey = $surveyRepository->findOneBy(['id' => $survey_id]);
            if (!$survey || !$survey->isEnable()) {
                $this->addFlash('error', 'Le sondage a expiré !');
                return $this->retirectToPreviousRoute($request, $defaultRoute);
            }

            //check if user has answer
            $has_answer = false;
            if ($this->getUser()) {
                $has_answer = $survey->countUserAnswers($this->getUser()->getId()) > 0;
            }
            if (!$has_answer) {
                $this->addFlash('error', 'Vous devez d\'abord répondre au sondage (voter) !');
                return $this->retirectToPreviousRoute($request, $defaultRoute);
            }

            $comment = new SurveyComments();
            $comment->setUser($this->getUser());
            $comment->setComment($request->get('comment'));
            $comment->setSurvey($survey);

            //validate entity
            $errors = $validator->validate($comment);
            if (count($errors) > 0) {
                $this->addFlash('error', $errors[0]->getMessage());
                return $this->retirectToPreviousRoute($request, $defaultRoute);
            }

            $em->persist($comment);
            $em->flush();

            $this->addFlash('notice', 'Merci pour votre commentaire !');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue..');
        }

        return $this->retirectToPreviousRoute($request, $defaultRoute);
    }

    /**
     * @Route("/delete/survey_comment/{survey_id}/{comment_id}", name="delete_survey_comment", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteSurveyComment(Request $request, $comment_id, $survey_id, EntityManagerInterface $em, SurveyRepository $surveyRepository, SurveyCommentsRepository $commentsRepository)
    {
        $defaultRoute = 'sondages';

        try {
            //check survey
            $survey = $surveyRepository->findOneBy(['id' => $survey_id]);
            if (!$survey || !$survey->isEnable()) {
                $this->addFlash('error', 'Le sondage a expiré !');
                return $this->retirectToPreviousRoute($request, $defaultRoute);
            }

            //check comment
            $comment = $commentsRepository->findOneBy(['id' => $comment_id]);
            if (!$comment) {
                $this->addFlash('error', 'Cette commentaire n\'existe pas..');
                return $this->retirectToPreviousRoute($request, $defaultRoute);
            }

            //check user owner comment
            if ($comment->getUser()->getId() !== $this->getUser()->getId()) {
                $this->addFlash('error', 'Vous ne pouvez pas supprimer ce commentaire.');
                return $this->retirectToPreviousRoute($request, $defaultRoute);
            }

            $em->remove($comment);
            $em->flush();

            $this->addFlash('notice', 'Votre commentaire a été supprimé !');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue..');
        }

        return $this->retirectToPreviousRoute($request, $defaultRoute);
    }

    /**
     * @Route("/sondages/archive", name="archived_sondages")
     */
    public function showArchivedSondages(Request $request, PaginatorInterface $paginator, SurveyRepository $surveyRepository)
    {
        $data = $surveyRepository->findBy([], ['fromTheDate' => 'ASC']);

        $archived_sondages = array_filter($data, function ($val) {
            return !$val->isEnable();
        });

        $sondages = $paginator->paginate(
            $archived_sondages,
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5 // Nombre de résultats par page
        );

        return $this->render('survey/list_archive.html.twig', [
            'archived_sondages' => $sondages,
            'total_archived_sondages' => count($archived_sondages),
        ]);
    }

    private function retirectToPreviousRoute(Request $request, $defaultRoute)
    {
        $previous = $request->headers->get('referer');
        if ($previous) {
            return $this->redirect($previous);
        } else {
            return $this->redirectToRoute($defaultRoute);
        }
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
