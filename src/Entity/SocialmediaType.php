<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SocialmediaTypeRepository")
 */
class SocialmediaType
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
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSocialmedia", mappedBy="socialmedia_type")
     */
    private $userSocialmedia;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SpoilShare", mappedBy="social", orphanRemoval=true)
     */
    private $spoilShares;

    public function __construct()
    {
        $this->userSocialmedia = new ArrayCollection();
        $this->spoilShares = new ArrayCollection();
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
     * @return Collection|UserSocialmedia[]
     */
    public function getUserSocialmedia(): Collection
    {
        return $this->userSocialmedia;
    }

    public function addUserSocialmedia(UserSocialmedia $userSocialmedia): self
    {
        if (!$this->userSocialmedia->contains($userSocialmedia)) {
            $this->userSocialmedia[] = $userSocialmedia;
            $userSocialmedia->setSocialmediaType($this);
        }

        return $this;
    }

    public function removeUserSocialmedia(UserSocialmedia $userSocialmedia): self
    {
        if ($this->userSocialmedia->contains($userSocialmedia)) {
            $this->userSocialmedia->removeElement($userSocialmedia);
            // set the owning side to null (unless already changed)
            if ($userSocialmedia->getSocialmediaType() === $this) {
                $userSocialmedia->setSocialmediaType(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
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
            $spoilShare->setSocial($this);
        }

        return $this;
    }

    public function removeSpoilShare(SpoilShare $spoilShare): self
    {
        if ($this->spoilShares->contains($spoilShare)) {
            $this->spoilShares->removeElement($spoilShare);
            // set the owning side to null (unless already changed)
            if ($spoilShare->getSocial() === $this) {
                $spoilShare->setSocial(null);
            }
        }

        return $this;
    }

}
