<?php

namespace App\Entity;

use App\Repository\RateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RateRepository::class)]
class Rate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups("rates")]
    private ?int $Stars = null;

    #[ORM\Column(length: 255)]
    #[Groups("rates")]
    private ?string $Review = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("rates")]
    private ?\DateTimeInterface $CreatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'rates')]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'rates')]
    #[Groups("rates")]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStars(): ?int
    {
        return $this->Stars;
    }

    public function setStars(?int $Stars): static
    {
        $this->Stars = $Stars;

        return $this;
    }

    public function getReview(): ?string
    {
        return $this->Review;
    }

    public function setReview(string $Review): static
    {
        $this->Review = $Review;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): static
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
