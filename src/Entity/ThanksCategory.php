<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ThanksCategoryRepository")
 */
class ThanksCategory
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Thanks", mappedBy="thanks_category", cascade={"persist", "remove"})
     */
    private $thanks;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subtitle;

    public function __construct()
    {
        $this->thanks = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Thanks[]
     */
    public function getThanks(): Collection
    {
        return $this->thanks;
    }

    public function addThank(Thanks $thank): self
    {
        if (!$this->thanks->contains($thank)) {
            $this->thanks[] = $thank;
            $thank->setThanksCategory($this);
        }

        return $this;
    }

    public function removeThank(Thanks $thank): self
    {
        if ($this->thanks->contains($thank)) {
            $this->thanks->removeElement($thank);
            // set the owning side to null (unless already changed)
            if ($thank->getThanksCategory() === $this) {
                $thank->setThanksCategory(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->getName();
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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }
}
