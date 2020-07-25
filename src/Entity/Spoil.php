<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpoilRepository")
 * @Vich\Uploadable
 */
class Spoil
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $createdBy;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shareGoal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SpoilShare", mappedBy="spoil", orphanRemoval=true)
     */
    private $spoilShares;

    /**
     * @Vich\UploadableField(mapping="spoils_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SpoilGoalType", inversedBy="spoils")
     */
    private $goal_type;

    /**
     * @ORM\ManyToMany(targetEntity=Attachment::class, cascade={"persist"}, orphanRemoval=true)
     */
    private $images;

    public function __construct()
    {
        $this->spoilShares = new ArrayCollection();
        $this->setCreatedAt(new DateTime('now'));
        $this->images = new ArrayCollection();
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        // if ($image) {
        // if 'updatedAt' is not defined in your entity, use another property
        // $this->updatedAt = new \DateTime('now');
        // }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getShareGoal(): ?int
    {
        return $this->shareGoal;
    }

    public function setShareGoal(?int $shareGoal): self
    {
        $this->shareGoal = $shareGoal;

        return $this;
    }

    /**
     * @return Collection|SpoilShare[]
     */
    public function getSpoilShares(): Collection
    {
        return $this->spoilShares;
    }

    public function addSpoilShare(SpoilShare $spoilShare): self
    {
        if (!$this->spoilShares->contains($spoilShare)) {
            $this->spoilShares[] = $spoilShare;
            $spoilShare->setSpoil($this);
        }

        return $this;
    }

    public function removeSpoilShare(SpoilShare $spoilShare): self
    {
        if ($this->spoilShares->contains($spoilShare)) {
            $this->spoilShares->removeElement($spoilShare);
            // set the owning side to null (unless already changed)
            if ($spoilShare->getSpoil() === $this) {
                $spoilShare->setSpoil(null);
            }
        }

        return $this;
    }

    public function getCurrentSocialShare()
    {
        return count($this->getSpoilShares());
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

    public function getGoalType(): ?SpoilGoalType
    {
        return $this->goal_type;
    }

    public function setGoalType(?SpoilGoalType $goal_type): self
    {
        $this->goal_type = $goal_type;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @return Collection|Attachment[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Attachment $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
        }

        return $this;
    }

    public function removeImage(Attachment $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }

        return $this;
    }
}
