<?php

namespace App\Entity;

use App\Repository\PaymentOffersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentOffersRepository::class)
 */
class PaymentOffers
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PaymentTypes::class, inversedBy="paymentOffers")
     */
    private $payment_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $debit_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $credit_receive;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $createdBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentType(): ?PaymentTypes
    {
        return $this->payment_type;
    }

    public function setPaymentType(?PaymentTypes $payment_type): self
    {
        $this->payment_type = $payment_type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
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

    public function getDebitPrice(): ?string
    {
        return $this->debit_price;
    }

    public function setDebitPrice(string $debit_price): self
    {
        $this->debit_price = $debit_price;

        return $this;
    }

    public function getCreditReceive(): ?int
    {
        return $this->credit_receive;
    }

    public function setCreditReceive(int $credit_receive): self
    {
        $this->credit_receive = $credit_receive;

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
}
