<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyAnswersRepository")
 */
class SurveyAnswers
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
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("survey_answer:read")
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Survey", inversedBy="surveyAnswers")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $survey;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="surveyAnswers")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SurveyAnswerList", inversedBy="surveyAnswers")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $answer;

    public function __construct()
    {
        $this->setDate(new DateTime('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): self
    {
        $this->survey = $survey;

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

    public function __toString()
    {
        return "Answers #" . $this->getUser()->getUsername();
    }

    public function getAnswer(): ?SurveyAnswerList
    {
        return $this->answer;
    }

    public function setAnswer(?SurveyAnswerList $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}
