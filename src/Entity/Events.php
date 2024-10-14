<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventsRepository::class)]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 750)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(nullable: true)]
    private ?int $paf = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $terrain_type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $weather = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $temperature = null;

    #[ORM\Column(nullable: true)]
    private ?bool $beginner_friendly = null;

    #[ORM\Column(nullable: true)]
    private ?bool $equipement_rental = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $referent = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Coordinates $coordinates = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: CarPoolingOffer::class)]
    private Collection $carPoolingOffers;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->carPoolingOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getPaf(): ?int
    {
        return $this->paf;
    }

    public function setPaf(?int $paf): static
    {
        $this->paf = $paf;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getTerrainType(): ?string
    {
        return $this->terrain_type;
    }

    public function setTerrainType(?string $terrain_type): static
    {
        $this->terrain_type = $terrain_type;

        return $this;
    }

    public function getWeather(): ?string
    {
        return $this->weather;
    }

    public function setWeather(?string $weather): static
    {
        $this->weather = $weather;

        return $this;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(?string $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function isBeginnerFriendly(): ?bool
    {
        return $this->beginner_friendly;
    }

    public function setBeginnerFriendly(?bool $beginner_friendly): static
    {
        $this->beginner_friendly = $beginner_friendly;

        return $this;
    }

    public function isEquipementRental(): ?bool
    {
        return $this->equipement_rental;
    }

    public function setEquipementRental(?bool $equipement_rental): static
    {
        $this->equipement_rental = $equipement_rental;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getReferent(): ?User
    {
        return $this->referent;
    }

    public function setReferent(?User $referent): static
    {
        $this->referent = $referent;

        return $this;
    }

    public function getCoordinates(): ?Coordinates
    {
        return $this->coordinates;
    }

    public function setCoordinates(?Coordinates $coordinates): static
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getCarPoolingOffers(): Collection
    {
        return $this->carPoolingOffers;
    }

    public function addCarPoolingOffer(CarPoolingOffer $carPoolingOffer): static
    {
        if (!$this->carPoolingOffers->contains($carPoolingOffer)) {
            $this->carPoolingOffers->add($carPoolingOffer);
            $carPoolingOffer->setEvent($this);
        }

        return $this;
    }

    public function removeCarPoolingOffer(CarPoolingOffer $carPoolingOffer): static
    {
        if ($this->carPoolingOffers->removeElement($carPoolingOffer)) {
            if ($carPoolingOffer->getEvent() === $this) {
                $carPoolingOffer->setEvent(null);
            }
        }

        return $this;
    }
}
