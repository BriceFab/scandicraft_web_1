<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyAnswerListRepository")
 * @UniqueEntity(fields={"answer"}, message="Il existe déjà la même réponse")
 */
class SurveyAnswerList
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private $answer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="surveyAnswerLists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Survey", mappedBy="answers_list")
     */
    private $surveys;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyAnswers", mappedBy="answer", orphanRemoval=true)
     */
    private $surveyAnswers;

    public function __construct()
    {
        $this->surveys = new ArrayCollection();
        $this->surveyAnswers = new ArrayCollection();
    }

    public function countAnswers() {
        return count($this->getSurveyAnswers());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|Survey[]
     */
    public function getSurveys(): Collection
    {
        return $this->surveys;
    }

    public function addSurvey(Survey $survey): self
    {
        if (!$this->surveys->contains($survey)) {
            $this->surveys[] = $survey;
            $survey->addAnswersList($this);
        }

        return $this;
    }

    public function removeSurvey(Survey $survey): self
    {
        if ($this->surveys->contains($survey)) {
            $this->surveys->removeElement($survey);
            $survey->removeAnswersList($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getAnswer();
    }

    /**
     * @return Collection|SurveyAnswers[]
     */
    public function getSurveyAnswers(): Collection
    {
        return $this->surveyAnswers;
    }

    public function addSurveyAnswer(SurveyAnswers $surveyAnswer): self
    {
        if (!$this->surveyAnswers->contains($surveyAnswer)) {
            $this->surveyAnswers[] = $surveyAnswer;
            $surveyAnswer->setAnswer($this);
        }

        return $this;
    }

    public function removeSurveyAnswer(SurveyAnswers $surveyAnswer): self
    {
        if ($this->surveyAnswers->contains($surveyAnswer)) {
            $this->surveyAnswers->removeElement($surveyAnswer);
            // set the owning side to null (unless already changed)
            if ($surveyAnswer->getAnswer() === $this) {
                $surveyAnswer->setAnswer(null);
            }
        }

        return $this;
    }
}
