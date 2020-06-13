<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpoilShareRepository")
 */
class SpoilShare
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="spoilShares")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Spoil", inversedBy="spoilShares")
     * @ORM\JoinColumn(nullable=false)
     */
    private $spoil;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SocialmediaType", inversedBy="spoilShares")
     * @ORM\JoinColumn(nullable=false)
     */
    private $social;

    /**
     * @ORM\Column(type="datetime")
     */
    private $shareAt;

    public function __construct()
    {
        $this->setShareAt(new DateTime('now'));
    }

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

    public function getSpoil(): ?Spoil
    {
        return $this->spoil;
    }

    public function setSpoil(?Spoil $spoil): self
    {
        $this->spoil = $spoil;

        return $this;
    }

    public function getSocial(): ?SocialmediaType
    {
        return $this->social;
    }

    public function setSocial(?SocialmediaType $social): self
    {
        $this->social = $social;

        return $this;
    }

    public function getShareAt(): ?\DateTimeInterface
    {
        return $this->shareAt;
    }

    public function setShareAt(\DateTimeInterface $shareAt): self
    {
        $this->shareAt = $shareAt;

        return $this;
    }

    public function __toString()
    {
        return $this->getSocial()->getName() . ' - ' . $this->getUser()->getUsername();        
    }

}
