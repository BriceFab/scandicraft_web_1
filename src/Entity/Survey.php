<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyRepository")
 */
class Survey
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $answer_delay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="surveys")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyAnswers", mappedBy="survey")
     */
    private $surveyAnswers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SurveyAnswerList", inversedBy="surveys")
     */
    private $answers_list;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    public function __construct()
    {
        $this->surveyAnswers = new ArrayCollection();
        $this->answers_list = new ArrayCollection();
        $this->setCreatedAt(new DateTime('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAnswerDelay(): ?int
    {
        return $this->answer_delay;
    }

    public function setAnswerDelay(int $answer_delay): self
    {
        $this->answer_delay = $answer_delay;

        return $this;
    }

    /**
     * @return Collection|SurveyAnswers[]
     */
    public function getAnswersList(): Collection
    {
        return $this->answers_list;
    }

    public function setAnswersList(array $answers_list): self
    {
        $this->answers_list = $answers_list;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
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
            $surveyAnswer->setSurvey($this);
        }

        return $this;
    }

    public function removeSurveyAnswer(SurveyAnswers $surveyAnswer): self
    {
        if ($this->surveyAnswers->contains($surveyAnswer)) {
            $this->surveyAnswers->removeElement($surveyAnswer);
            // set the owning side to null (unless already changed)
            if ($surveyAnswer->getSurvey() === $this) {
                $surveyAnswer->setSurvey(null);
            }
        }

        return $this;
    }

    public function addAnswersList(SurveyAnswerList $answersList): self
    {
        dump($this->answers_list);

        if (!$this->answers_list->contains($answersList)) {
            $this->answers_list[] = $answersList;
        }

        return $this;
    }

    public function removeAnswersList(SurveyAnswerList $answersList): self
    {
        if ($this->answers_list->contains($answersList)) {
            $this->answers_list->removeElement($answersList);
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function countAnswers(): int
    {
        return count($this->getSurveyAnswers());
    }

    public function getLimitDate(): ?\DateTimeInterface
    {
        return $this->getCreatedAt()->modify("+{$this->getAnswerDelay()} hours"); #TODO: c'est bugé
    }
}
