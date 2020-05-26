<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\InheritanceType("JOINED")
 * @ORM\Entity(repositoryClass="App\Repository\ForumCategoryRepository")
 */
class ForumCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $priority;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="forumCategories")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $createdBy;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ForumSubCategory", mappedBy="category", cascade={"persist"})
     */
    protected $forumSubCategories;

    public function __construct()
    {
        $this->forumSubCategories = new ArrayCollection();
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

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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
     * @return Collection|ForumSubCategory[]
     */
    public function getForumSubCategories(): Collection
    {
        return $this->forumSubCategories;
    }

    public function addForumSubCategory(ForumSubCategory $forumSubCategory): self
    {
        if (!$this->forumSubCategories->contains($forumSubCategory)) {
            $this->forumSubCategories[] = $forumSubCategory;
            $forumSubCategory->setCategory($this);
        }

        return $this;
    }

    public function removeForumSubCategory(ForumSubCategory $forumSubCategory): self
    {
        if ($this->forumSubCategories->contains($forumSubCategory)) {
            $this->forumSubCategories->removeElement($forumSubCategory);
            // set the owning side to null (unless already changed)
            if ($forumSubCategory->getCategory() === $this) {
                $forumSubCategory->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
