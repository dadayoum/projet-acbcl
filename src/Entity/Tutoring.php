<?php

namespace App\Entity;

use App\Repository\TutoringRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TutoringRepository::class)
 */
class Tutoring
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $otherMembers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="tutoring")
     */
    private $userTutoring;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getOtherMembers(): ?string
    {
        return $this->otherMembers;
    }

    public function setOtherMembers(?string $otherMembers): self
    {
        $this->otherMembers = $otherMembers;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUserTutoring(): ?User
    {
        return $this->userTutoring;
    }

    public function setUserTutoring(?User $userTutoring): self
    {
        // unset the owning side of the relation if necessary
        if ($userTutoring === null && $this->userTutoring !== null) {
            $this->userTutoring->setTutoring(null);
        }

        // set the owning side of the relation if necessary
        if ($userTutoring !== null && $userTutoring->getTutoring() !== $this) {
            $userTutoring->setTutoring($this);
        }

        $this->userTutoring = $userTutoring;

        return $this;
    }
}
