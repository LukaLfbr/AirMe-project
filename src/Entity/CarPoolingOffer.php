<?php

namespace App\Entity;

use App\Repository\CarPoolingOfferRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarPoolingOfferRepository::class)]
class CarPoolingOffer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $departure_location = null;

    #[ORM\Column(length: 255)]
    private ?string $arrival_location = null;

    #[ORM\Column(length: 255)]
    private ?string $departure_time = null;

    #[ORM\Column(nullable: true)]
    private ?int $seats_available = null;

    #[ORM\ManyToOne(inversedBy: 'creator_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    #[ORM\ManyToOne(inversedBy: 'carPoolingOffers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Events $event = null;

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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDepartureLocation(): ?string
    {
        return $this->departure_location;
    }

    public function setDepartureLocation(string $departure_location): static
    {
        $this->departure_location = $departure_location;

        return $this;
    }

    public function getArrivalLocation(): ?string
    {
        return $this->arrival_location;
    }

    public function setArrivalLocation(string $arrival_location): static
    {
        $this->arrival_location = $arrival_location;

        return $this;
    }

    public function getDepartureTime(): ?string
    {
        return $this->departure_time;
    }

    public function setDepartureTime(string $departure_time): static
    {
        $this->departure_time = $departure_time;

        return $this;
    }

    public function getSeatsAvailable(): ?int
    {
        return $this->seats_available;
    }

    public function setSeatsAvailable(?int $seats_available): static
    {
        $this->seats_available = $seats_available;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function getEvent(): ?Events
    {
        return $this->event;
    }

    public function setEvent(?Events $event): static
    {
        $this->event = $event;

        return $this;
    }
}
