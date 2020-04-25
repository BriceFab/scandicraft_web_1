<?php

namespace App\Controller;

use App\Entity\SurveyAnswers;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SurveyAnswersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ApiSurveyController extends AbstractController
{
    /**
     * @Route("/api/survey_answers", name="api_get_survey_answers", methods={"GET"})
     */
    public function getSurveyAnswers(SurveyAnswersRepository $repo)
    {
        return $this->json($repo->findAll(), Response::HTTP_OK, [], ['groups' => 'survey_answer:read']);
    }

    /**
     * @Route("/api/survey_answer", name="api_post_survey_answer", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function postSurveyAnswer(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $data = $request->getContent();

        try {

            $entity = $serializer->deserialize($data, SurveyAnswers::class, 'json');

            $errors = $validator->validate($entity);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $em->persist($entity);
            $em->flush();

            return $this->json($entity, Response::HTTP_CREATED, [], ['groups' => 'survey_answer:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
