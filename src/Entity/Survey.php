<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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
    private $fromTheDate;

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
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyAnswers", mappedBy="survey", cascade={"persist", "remove"}, orphanRemoval=true)
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyComments", mappedBy="survey", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"commentAt" = "DESC"})
     */
    private $surveyComments;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->surveyAnswers = new ArrayCollection();
        $this->answers_list = new ArrayCollection();
        $this->setFromTheDate(new DateTime('now'));
        $this->surveyComments = new ArrayCollection();
    }

    public function countTotalComments()
    {
        return count($this->getSurveyComments());
    }

    public function countUserAnswers($user_id)
    {
        $nbr = 0;
        foreach ($this->getSurveyAnswers() as $answer) {
            if ($answer->getUser()->getId() == $user_id) {
                $nbr++;
            }
        }
        return $nbr;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromTheDate(): ?\DateTimeInterface
    {
        return $this->fromTheDate;
    }

    public function setFromTheDate(\DateTimeInterface $fromTheDate): self
    {
        $this->fromTheDate = $fromTheDate;

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

    public function countAnswersList()
    {
        return $this->getAnswersList()->count();
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
        $date_created = clone $this->getFromTheDate();
        $date_limit = $date_created->modify("+{$this->getAnswerDelay()} hours");

        return $date_limit;
    }

    public function isEnable()
    {
        return $this->getLimitDate() > new DateTime('now');
    }

    /**
     * @return Collection|SurveyComments[]
     */
    public function getSurveyComments(): Collection
    {
        return $this->surveyComments;
    }

    public function addSurveyComment(SurveyComments $surveyComment): self
    {
        if (!$this->surveyComments->contains($surveyComment)) {
            $this->surveyComments[] = $surveyComment;
            $surveyComment->setSurvey($this);
        }

        return $this;
    }

    public function removeSurveyComment(SurveyComments $surveyComment): self
    {
        if ($this->surveyComments->contains($surveyComment)) {
            $this->surveyComments->removeElement($surveyComment);
            // set the owning side to null (unless already changed)
            if ($surveyComment->getSurvey() === $this) {
                $surveyComment->setSurvey(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
