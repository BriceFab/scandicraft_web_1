<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StaffRepository")
 */
class Staff
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $presentation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $since;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $head_link;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSocialmedia", mappedBy="staff", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $social_media;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\StaffCategory", inversedBy="staff")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function __construct()
    {
        $this->social_media = new ArrayCollection();
        $this->setSince(new DateTime('now'));
    }

    public function __toString() {
        return "Staff " . $this->getUser()->getUsername();
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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getSince(): ?\DateTimeInterface
    {
        return $this->since;
    }

    public function setSince(\DateTimeInterface $since): self
    {
        $this->since = $since;

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

    /**
     * @return Collection|UserSocialmedia[]
     */
    public function getSocialMedia(): Collection
    {
        return $this->social_media;
    }

    public function addSocialMedium(UserSocialmedia $socialMedium): self
    {
        if (!$this->social_media->contains($socialMedium)) {
            $this->social_media[] = $socialMedium;
            $socialMedium->setStaff($this);
        }

        return $this;
    }

    public function removeSocialMedium(UserSocialmedia $socialMedium): self
    {
        if ($this->social_media->contains($socialMedium)) {
            $this->social_media->removeElement($socialMedium);
            // set the owning side to null (unless already changed)
            if ($socialMedium->getStaff() === $this) {
                $socialMedium->setStaff(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?StaffCategory
    {
        return $this->category;
    }

    public function setCategory(?StaffCategory $category): self
    {
        $this->category = $category;

        return $this;
    }
}
