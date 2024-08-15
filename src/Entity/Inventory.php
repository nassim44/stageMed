<?php

namespace App\Entity;

use App\Repository\InventoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InventoryRepository::class)]
class Inventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("inventory")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("inventory")]
    private ?string $disponibilte = null;

    #[ORM\Column]
    #[Groups("users")]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'Inventories')]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'Inventories')]
    private ?WareHouse $wareHouse = null;

    #[ORM\OneToMany(mappedBy: 'inventory', targetEntity: SerialNumber::class)]
    private Collection $serialNumbers;

    public function __construct()
    {
        $this->serialNumbers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDisponibilte(): ?string
    {
        return $this->disponibilte;
    }

    public function setDisponibilte(string $disponibilte): static
    {
        $this->disponibilte = $disponibilte;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

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

    public function getWareHouse(): ?WareHouse
    {
        return $this->wareHouse;
    }

    public function setWareHouse(?WareHouse $wareHouse): static
    {
        $this->wareHouse = $wareHouse;

        return $this;
    }

    /**
     * @return Collection<int, SerialNumber>
     */
    public function getSerialNumbers(): Collection
    {
        return $this->serialNumbers;
    }

    public function addSerialNumber(SerialNumber $serialNumber): static
    {
        if (!$this->serialNumbers->contains($serialNumber)) {
            $this->serialNumbers->add($serialNumber);
            $serialNumber->setInventory($this);
        }

        return $this;
    }

    public function removeSerialNumber(SerialNumber $serialNumber): static
    {
        if ($this->serialNumbers->removeElement($serialNumber)) {
            // set the owning side to null (unless already changed)
            if ($serialNumber->getInventory() === $this) {
                $serialNumber->setInventory(null);
            }
        }

        return $this;
    }


}
