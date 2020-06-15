<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpoilGoalTypeRepository")
 */
class SpoilGoalType
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
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Spoil", mappedBy="goal_type")
     */
    private $spoils;

    public function __construct()
    {
        $this->spoils = new ArrayCollection();
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|Spoil[]
     */
    public function getSpoils(): Collection
    {
        return $this->spoils;
    }

    public function addSpoil(Spoil $spoil): self
    {
        if (!$this->spoils->contains($spoil)) {
            $this->spoils[] = $spoil;
            $spoil->setGoalType($this);
        }

        return $this;
    }

    public function removeSpoil(Spoil $spoil): self
    {
        if ($this->spoils->contains($spoil)) {
            $this->spoils->removeElement($spoil);
            // set the owning side to null (unless already changed)
            if ($spoil->getGoalType() === $this) {
                $spoil->setGoalType(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getLabel();
    }
}
