<?php

namespace App\Entity;

use App\Repository\SerialNumberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SerialNumberRepository::class)]
class SerialNumber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $SerialNumber = null;

    #[ORM\ManyToOne(inversedBy: 'serialNumbers')]
    private ?Inventory $inventory = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerialNumber(): ?string
    {
        return $this->SerialNumber;
    }

    public function setSerialNumber(string $SerialNumber): static
    {
        $this->SerialNumber = $SerialNumber;

        return $this;
    }

    public function getInventory(): ?Inventory
    {
        return $this->inventory;
    }

    public function setInventory(?Inventory $inventory): static
    {
        $this->inventory = $inventory;

        return $this;
    }
}
