<?php

namespace App\Entity;

use App\Repository\PaymentTypesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=PaymentTypesRepository::class)
 */
class PaymentTypes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Ignore()
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
     * @ORM\Column(type="string", length=255)
     */
    private $help_text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dynamic_key;

    /**
     * @ORM\Column(type="datetime")
//     * @Serializer\Ignore()
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @Serializer\Ignore()
     */
    private $createdBy;

    /**
     * @ORM\OneToMany(targetEntity=PaymentOffers::class, mappedBy="payment_type")
     * @Serializer\Ignore()
     */
    private $paymentOffers;

    public function __construct()
    {
        $this->paymentOffers = new ArrayCollection();
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

    public function getHelpText(): ?string
    {
        return $this->help_text;
    }

    public function setHelpText(string $help_text): self
    {
        $this->help_text = $help_text;

        return $this;
    }

    public function getDynamicKey(): ?string
    {
        return $this->dynamic_key;
    }

    public function setDynamicKey(string $dynamic_key): self
    {
        $this->dynamic_key = $dynamic_key;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection|PaymentOffers[]
     */
    public function getPaymentOffers(): Collection
    {
        return $this->paymentOffers;
    }

    public function addPaymentOffer(PaymentOffers $paymentOffer): self
    {
        if (!$this->paymentOffers->contains($paymentOffer)) {
            $this->paymentOffers[] = $paymentOffer;
            $paymentOffer->setPaymentType($this);
        }

        return $this;
    }

    public function removePaymentOffer(PaymentOffers $paymentOffer): self
    {
        if ($this->paymentOffers->contains($paymentOffer)) {
            $this->paymentOffers->removeElement($paymentOffer);
            // set the owning side to null (unless already changed)
            if ($paymentOffer->getPaymentType() === $this) {
                $paymentOffer->setPaymentType(null);
            }
        }

        return $this;
    }
}
