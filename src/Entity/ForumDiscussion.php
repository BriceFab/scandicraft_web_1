<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumDiscussionRepository")
 */
class ForumDiscussion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    private $pin;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    private $archive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forumDiscussions")
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumSubCategory", inversedBy="forumDiscussions")
     */
    private $sub_category;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    private $staff_only;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumDiscussionAnswer", mappedBy="discussion", cascade={"remove"})
     */
    private $forumDiscussionAnswers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumDiscussionStatus", inversedBy="forumDiscussions")
     */
    private $status;

    public function __construct()
    {
        $this->forumDiscussionAnswers = new ArrayCollection();
        $this->setPin(false);
        $this->setArchive(false);
        $this->setStaffOnly(false);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getPin(): ?bool
    {
        return $this->pin;
    }

    public function setPin(?bool $pin): self
    {
        $this->pin = $pin;

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(?bool $archive): self
    {
        $this->archive = $archive;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getSubCategory(): ?ForumSubCategory
    {
        return $this->sub_category;
    }

    public function setSubCategory(?ForumSubCategory $sub_category): self
    {
        $this->sub_category = $sub_category;

        return $this;
    }

    public function getStaffOnly(): ?bool
    {
        return $this->staff_only;
    }

    public function setStaffOnly(?bool $staff_only): self
    {
        $this->staff_only = $staff_only;

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

    /**
     * @return Collection|ForumDiscussionAnswer[]
     */
    public function getForumDiscussionAnswers(): Collection
    {
        return $this->forumDiscussionAnswers;
    }

    public function addForumDiscussionAnswer(ForumDiscussionAnswer $forumDiscussionAnswer): self
    {
        if (!$this->forumDiscussionAnswers->contains($forumDiscussionAnswer)) {
            $this->forumDiscussionAnswers[] = $forumDiscussionAnswer;
            $forumDiscussionAnswer->setDiscussion($this);
        }

        return $this;
    }

    public function removeForumDiscussionAnswer(ForumDiscussionAnswer $forumDiscussionAnswer): self
    {
        if ($this->forumDiscussionAnswers->contains($forumDiscussionAnswer)) {
            $this->forumDiscussionAnswers->removeElement($forumDiscussionAnswer);
            // set the owning side to null (unless already changed)
            if ($forumDiscussionAnswer->getDiscussion() === $this) {
                $forumDiscussionAnswer->setDiscussion(null);
            }
        }

        return $this;
    }

    public function countAnswers() {
        return count($this->getForumDiscussionAnswers());
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getStatus(): ?ForumDiscussionStatus
    {
        return $this->status;
    }

    public function setStatus(?ForumDiscussionStatus $status): self
    {
        $this->status = $status;

        return $this;
    }
}
