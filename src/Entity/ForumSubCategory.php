<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumSubCategoryRepository")
 */
class ForumSubCategory extends ForumCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    protected $writable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumCategory", inversedBy="forumSubCategories")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $category;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumDiscussion", mappedBy="sub_category", cascade={"remove"})
     */
    private $forumDiscussions;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    private $accept_staff_only;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sub_title;

    public function __construct()
    {
        parent::__construct();
        $this->forumDiscussions = new ArrayCollection();
        $this->setAcceptStaffOnly(false);
        $this->setWritable(true);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWritable(): ?bool
    {
        return $this->writable;
    }

    public function setWritable(bool $writable): self
    {
        $this->writable = $writable;

        return $this;
    }

    public function getCategory(): ?ForumCategory
    {
        return $this->category;
    }

    public function setCategory(?ForumCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return Collection|ForumDiscussion[]
     */
    public function getForumDiscussions(): Collection
    {
        return $this->forumDiscussions;
    }

    public function addForumDiscussion(ForumDiscussion $forumDiscussion): self
    {
        if (!$this->forumDiscussions->contains($forumDiscussion)) {
            $this->forumDiscussions[] = $forumDiscussion;
            $forumDiscussion->setSubCategory($this);
        }

        return $this;
    }

    public function removeForumDiscussion(ForumDiscussion $forumDiscussion): self
    {
        if ($this->forumDiscussions->contains($forumDiscussion)) {
            $this->forumDiscussions->removeElement($forumDiscussion);
            // set the owning side to null (unless already changed)
            if ($forumDiscussion->getSubCategory() === $this) {
                $forumDiscussion->setSubCategory(null);
            }
        }

        return $this;
    }

    public function getAcceptStaffOnly(): ?bool
    {
        return $this->accept_staff_only;
    }

    public function setAcceptStaffOnly(?bool $accept_staff_only): self
    {
        $this->accept_staff_only = $accept_staff_only;

        return $this;
    }

    public function countMessages()
    {
        $messages = 0;

        foreach ($this->getForumDiscussions() as $key => $discussion) {
            /** @var ForumDiscussion $discussion */
            $messages += count($discussion->getForumDiscussionAnswers());
        }

        return $messages;
    }

    public function countDiscussion()
    {
        return count($this->getForumDiscussions());
    }

    public function getSubTitle(): ?string
    {
        return $this->sub_title;
    }

    public function setSubTitle(?string $sub_title): self
    {
        $this->sub_title = $sub_title;

        return $this;
    }
}
