<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ThanksRepository")
 */
class Thanks
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ThanksCategory", inversedBy="thanks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $thanks_category;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSocialmedia", mappedBy="thanks", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $social_media;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $head_link;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime('now'));
        $this->social_media = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getThanksCategory(): ?ThanksCategory
    {
        return $this->thanks_category;
    }

    public function setThanksCategory(?ThanksCategory $thanks_category): self
    {
        $this->thanks_category = $thanks_category;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

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

    public function __toString() {
        return "Remerciement pour " . $this->getUser()->getUsername();
    }

    /**
     * @return Collection|UserSocialmedia[]
     */
    public function getSocialmedia(): Collection
    {
        return $this->social_media;
    }

    public function addSocialMedium(UserSocialmedia $socialMedium): self
    {
        if (!$this->social_media->contains($socialMedium)) {
            $this->social_media[] = $socialMedium;
            $socialMedium->setThanks($this);
        }

        return $this;
    }

    public function removeSocialMedium(UserSocialmedia $socialMedium): self
    {
        if ($this->social_media->contains($socialMedium)) {
            $this->social_media->removeElement($socialMedium);
            // set the owning side to null (unless already changed)
            if ($socialMedium->getThanks() === $this) {
                $socialMedium->setThanks(null);
            }
        }

        return $this;
    }

    public function getHeadLink(): ?string
    {
        return $this->head_link;
    }

    public function setHeadLink(string $head_link): self
    {
        $this->head_link = $head_link;

        return $this;
    }
}
