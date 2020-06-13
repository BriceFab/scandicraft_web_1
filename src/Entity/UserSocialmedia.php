<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserSocialmediaRepository")
 */
class UserSocialmedia
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SocialmediaType", inversedBy="userSocialmedia")
     * @ORM\JoinColumn(nullable=false)
     */
    private $socialmedia_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Thanks", inversedBy="social_media")
     */
    private $thanks;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Staff", inversedBy="social_media")
     */
    private $staff;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userSocialmedia")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSocialmediaType(): ?SocialmediaType
    {
        return $this->socialmedia_type;
    }

    public function setSocialmediaType(?SocialmediaType $socialmedia_type): self
    {
        $this->socialmedia_type = $socialmedia_type;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getThanks(): ?Thanks
    {
        return $this->thanks;
    }

    public function setThanks(?Thanks $thanks): self
    {
        $this->thanks = $thanks;

        return $this;
    }

    public function getStaff(): ?Staff
    {
        return $this->staff;
    }

    public function setStaff(?Staff $staff): self
    {
        $this->staff = $staff;

        return $this;
    }

    public function __toString()
    {
        return $this->getSocialmediaType()->getName() . ' - ' . $this->getUrl();
    }
}
