<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumSubCategoryRepository")
 */
class ForumSubCategory extends ForumCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $writable;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ForumCategory", inversedBy="forumSubCategories")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $category;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWritable(): ?bool
    {
        return $this->writable;
    }

    public function setWritable(bool $writable): self
    {
        $this->writable = $writable;

        return $this;
    }

    public function getCategory(): ?ForumCategory
    {
        return $this->category;
    }

    public function setCategory(?ForumCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
