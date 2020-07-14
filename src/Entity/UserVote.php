<?php

namespace App\Entity;

use App\Repository\UserVoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserVoteRepository::class)
 */
class UserVote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userVotes")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $vote_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user_ip;

    /**
     * @ORM\ManyToOne(targetEntity=VoteSite::class, inversedBy="userVotes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vote_site;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getVoteId(): ?string
    {
        return $this->vote_id;
    }

    public function setVoteId(?string $vote_id): self
    {
        $this->vote_id = $vote_id;

        return $this;
    }

    public function getUserIp(): ?string
    {
        return $this->user_ip;
    }

    public function setUserIp(string $user_ip): self
    {
        $this->user_ip = $user_ip;

        return $this;
    }

    public function getVoteSite(): ?VoteSite
    {
        return $this->vote_site;
    }

    public function setVoteSite(?VoteSite $vote_site): self
    {
        $this->vote_site = $vote_site;

        return $this;
    }

    public function __toString()
    {
        return $this->getUser()->getUsername() . " - " . $this->getVoteId();
    }
}
