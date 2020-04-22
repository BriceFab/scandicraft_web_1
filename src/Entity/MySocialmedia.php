<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MySocialmediaRepository")
 */
class MySocialmedia
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
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SocialmediaType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $socialmedia_type;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSocialmediaType(): ?SocialmediaType
    {
        return $this->socialmedia_type;
    }

    public function setSocialmediaType(?SocialmediaType $socialmedia_type): self
    {
        $this->socialmedia_type = $socialmedia_type;

        return $this;
    }
}
