<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Survey;
use App\Entity\SurveyAnswers;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

final class SurveyDataPersister implements DataPersisterInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof SurveyAnswers;
    }

    public function persist($data, array $context = [])
    {
        // $survey = $this->em->getRepository(Survey::class)->findOneBy(['id' => 3]);
        // $data->setSurvey($survey);

        // dd($data);

        $this->em->persist($data);
        $this->em->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}
