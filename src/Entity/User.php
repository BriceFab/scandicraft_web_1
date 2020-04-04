<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=15, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasConfirmEmail = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_login;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Survey", mappedBy="user")
     */
    private $surveys;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyAnswerList", mappedBy="createdBy")
     */
    private $surveyAnswerLists;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SurveyAnswers", mappedBy="user")
     */
    private $surveyAnswers;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime('now'));
        $this->surveys = new ArrayCollection();
        $this->surveyAnswerLists = new ArrayCollection();
        $this->surveyAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return 'Y2vb}7km(TX!8j^P6LqEp5Q&P!b}4Xv2na%H7K8uUXiB>$Bz%z62[)444wzX5;94@TY7xQ$p:;Gs*L>eW6%8b5Ms?DGNX$Tu5v!3w32>4f%Dx.^t52+-F:hGh3z9;49V';
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getHasConfirmEmail(): ?bool
    {
        return $this->hasConfirmEmail;
    }

    public function setHasConfirmEmail(bool $hasConfirmEmail): self
    {
        $this->hasConfirmEmail = $hasConfirmEmail;

        return $this;
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

    public function __toString()
    {
        return $this->getUsername();
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->last_login;
    }

    public function setLastLogin(?\DateTimeInterface $last_login): self
    {
        $this->last_login = $last_login;

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
            $survey->setUser($this);
        }

        return $this;
    }

    public function removeSurvey(Survey $survey): self
    {
        if ($this->surveys->contains($survey)) {
            $this->surveys->removeElement($survey);
            // set the owning side to null (unless already changed)
            if ($survey->getUser() === $this) {
                $survey->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SurveyAnswerList[]
     */
    public function getSurveyAnswerLists(): Collection
    {
        return $this->surveyAnswerLists;
    }

    public function addSurveyAnswerList(SurveyAnswerList $surveyAnswerList): self
    {
        if (!$this->surveyAnswerLists->contains($surveyAnswerList)) {
            $this->surveyAnswerLists[] = $surveyAnswerList;
            $surveyAnswerList->setCreatedBy($this);
        }

        return $this;
    }

    public function removeSurveyAnswerList(SurveyAnswerList $surveyAnswerList): self
    {
        if ($this->surveyAnswerLists->contains($surveyAnswerList)) {
            $this->surveyAnswerLists->removeElement($surveyAnswerList);
            // set the owning side to null (unless already changed)
            if ($surveyAnswerList->getCreatedBy() === $this) {
                $surveyAnswerList->setCreatedBy(null);
            }
        }

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
            $surveyAnswer->setUser($this);
        }

        return $this;
    }

    public function removeSurveyAnswer(SurveyAnswers $surveyAnswer): self
    {
        if ($this->surveyAnswers->contains($surveyAnswer)) {
            $this->surveyAnswers->removeElement($surveyAnswer);
            // set the owning side to null (unless already changed)
            if ($surveyAnswer->getUser() === $this) {
                $surveyAnswer->setUser(null);
            }
        }

        return $this;
    }
}
