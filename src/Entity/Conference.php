<?php

namespace App\Entity;

use App\Repository\ConferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ConferenceRepository::class)
 */
class Conference
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $information;

    /**
     * @ORM\OneToMany(targetEntity=SessionEvent::class, mappedBy="conference", cascade={"persist", "remove"})
     */
    private $sessionsEvent;

    public function __construct()
    {
        $this->sessionsEvent = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(?string $information): self
    {
        $this->information = $information;

        return $this;
    }

    /**
     * @return Collection<int, SessionEvent>
     */
    public function getSessionsEvent(): Collection
    {
        return $this->sessionsEvent;
    }

    public function addSessionEvent(SessionEvent $sessionEvent): self
    {
        if (!$this->sessionsEvent->contains($sessionEvent)) {
            $this->sessionsEvent[] = $sessionEvent;
            $sessionEvent->setConference($this);
        }

        return $this;
    }

    public function removeSessionEvent(SessionEvent $sessionEvent): self
    {
        if ($this->sessionsEvent->removeElement($sessionEvent)) {
            // set the owning side to null (unless already changed)
            if ($sessionEvent->getConference() === $this) {
                $sessionEvent->setConference(null);
            }
        }

        return $this;
    }
}
