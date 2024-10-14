<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'validators.email.already.used')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\OneToMany(targetEntity: Events::class, mappedBy: 'referent')]
    private Collection $events;

    #[ORM\Column(nullable: true)]
    private ?int $phone_number = null;

    #[ORM\OneToMany(targetEntity: CarPoolingOffer::class, mappedBy: 'creator', orphanRemoval: true)]
    private Collection $creator_id;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->roles = ['ROLE_USER']; 
        $this->creator_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setReferent($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): static
    {
        if ($this->events->removeElement($event)) {
            if ($event->getReferent() === $this) {
                $event->setReferent(null);
            }
        }

        return $this;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?int $phone_number): static
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getCreatorId(): Collection
    {
        return $this->creator_id;
    }

    public function addCreatorId(CarPoolingOffer $creatorId): static
    {
        if (!$this->creator_id->contains($creatorId)) {
            $this->creator_id->add($creatorId);
            $creatorId->setCreator($this);
        }

        return $this;
    }

    public function removeCreatorId(CarPoolingOffer $creatorId): static
    {
        if ($this->creator_id->removeElement($creatorId)) {
            if ($creatorId->getCreator() === $this) {
                $creatorId->setCreator(null);
            }
        }

        return $this;
    }
}
