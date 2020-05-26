<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserIpRepository")
 */
class UserIp
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userIps")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ip;

    /**
     * @ORM\Column(type="datetime")
     */
    private $login_at;

    public function __construct()
    {
        $this->setLoginAt(new DateTime('now'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getLoginAt(): ?\DateTimeInterface
    {
        return $this->login_at;
    }

    public function setLoginAt(\DateTimeInterface $login_at): self
    {
        $this->login_at = $login_at;

        return $this;
    }

    public function __toString()
    {
        return $this->getIp();
    }
}
