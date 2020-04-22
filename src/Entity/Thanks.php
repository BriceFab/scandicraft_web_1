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
     * @ORM\OneToMany(targetEntity="App\Entity\UserSocialmedia", mappedBy="thanks")
     */
    private $socialmedia;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime('now'));
        $this->socialmedia = new ArrayCollection();
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

    /**
     * @return Collection|UserSocialmedia[]
     */
    public function getSocialmedia(): Collection
    {
        return $this->socialmedia;
    }

    public function addSocialmedia(UserSocialmedia $socialmedia): self
    {
        if (!$this->socialmedia->contains($socialmedia)) {
            $this->socialmedia[] = $socialmedia;
            $socialmedia->setThanks($this);
        }

        return $this;
    }

    public function removeSocialmedia(UserSocialmedia $socialmedia): self
    {
        if ($this->socialmedia->contains($socialmedia)) {
            $this->socialmedia->removeElement($socialmedia);
            // set the owning side to null (unless already changed)
            if ($socialmedia->getThanks() === $this) {
                $socialmedia->setThanks(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return "Remerciement pour " . $this->getUser()->getUsername();
    }
}
