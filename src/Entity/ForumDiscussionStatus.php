<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumDiscussionStatusRepository")
 */
class ForumDiscussionStatus
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
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $info;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumDiscussion", mappedBy="status")
     */
    private $forumDiscussions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    public function __construct()
    {
        $this->forumDiscussions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): self
    {
        $this->info = $info;

        return $this;
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
            $forumDiscussion->setStatus($this);
        }

        return $this;
    }

    public function removeForumDiscussion(ForumDiscussion $forumDiscussion): self
    {
        if ($this->forumDiscussions->contains($forumDiscussion)) {
            $this->forumDiscussions->removeElement($forumDiscussion);
            // set the owning side to null (unless already changed)
            if ($forumDiscussion->getStatus() === $this) {
                $forumDiscussion->setStatus(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getStatus();
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
