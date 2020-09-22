<?php

namespace App\Entity;

use App\Repository\StoreCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StoreCategoryRepository::class)
 */
class StoreCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\OneToMany(targetEntity=StoreArticle::class, mappedBy="category")
     */
    private $storeArticles;

    public function __construct()
    {
        $this->storeArticles = new ArrayCollection();
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

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * @return Collection|StoreArticle[]
     */
    public function getStoreArticles(): Collection
    {
        return $this->storeArticles;
    }

    public function addStoreArticle(StoreArticle $storeArticle): self
    {
        if (!$this->storeArticles->contains($storeArticle)) {
            $this->storeArticles[] = $storeArticle;
            $storeArticle->setCategory($this);
        }

        return $this;
    }

    public function removeStoreArticle(StoreArticle $storeArticle): self
    {
        if ($this->storeArticles->contains($storeArticle)) {
            $this->storeArticles->removeElement($storeArticle);
            // set the owning side to null (unless already changed)
            if ($storeArticle->getCategory() === $this) {
                $storeArticle->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
