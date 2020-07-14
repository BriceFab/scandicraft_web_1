<?php

namespace App\Entity;

use App\Repository\VoteSiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoteSiteRepository::class)
 */
class VoteSite
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
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="voteSites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="smallint")
     */
    private $time_wait_vote;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $vote_url;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=UserVote::class, mappedBy="vote_site", orphanRemoval=true)
     */
    private $userVotes;

    public function __construct()
    {
        $this->userVotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
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

    public function getTimeWaitVote(): ?int
    {
        return $this->time_wait_vote;
    }

    public function setTimeWaitVote(int $time_wait_vote): self
    {
        $this->time_wait_vote = $time_wait_vote;

        return $this;
    }

    public function getVoteUrl(): ?string
    {
        return $this->vote_url;
    }

    public function setVoteUrl(string $vote_url): self
    {
        $this->vote_url = $vote_url;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|UserVote[]
     */
    public function getUserVotes(): Collection
    {
        return $this->userVotes;
    }

    public function addUserVote(UserVote $userVote): self
    {
        if (!$this->userVotes->contains($userVote)) {
            $this->userVotes[] = $userVote;
            $userVote->setVoteSite($this);
        }

        return $this;
    }

    public function removeUserVote(UserVote $userVote): self
    {
        if ($this->userVotes->contains($userVote)) {
            $this->userVotes->removeElement($userVote);
            // set the owning side to null (unless already changed)
            if ($userVote->getVoteSite() === $this) {
                $userVote->setVoteSite(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
