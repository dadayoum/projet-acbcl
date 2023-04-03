<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paid;

    /**
     * @ORM\ManyToOne(targetEntity=SessionEvent::class, inversedBy="payments")
     */
    private $sessionEvent;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="payment")
     */
    private $userPayment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paymentType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getSessionEvent(): ?SessionEvent
    {
        return $this->sessionEvent;
    }

    public function setSessionEvent(?SessionEvent $sessionEvent): self
    {
        $this->sessionEvent = $sessionEvent;

        return $this;
    }

    public function getUserPayment(): ?User
    {
        return $this->userPayment;
    }

    public function setUserPayment(?User $userPayment): self
    {
        $this->userPayment = $userPayment;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(string $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }
}
