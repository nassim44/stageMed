<?php

namespace App\Entity;

use App\Repository\WareHouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WareHouseRepository::class)]
class WareHouse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("warehouse")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("warehouse")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups("warehouse")]
    private ?string $location = null;

    #[ORM\Column(length: 255)]
    #[Groups("warehouse")]
    private ?int $capacity = null;

    #[ORM\Column(length: 255)]
    #[Groups("warehouse")]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'wareHouse', targetEntity: Inventory::class)]
    private Collection $Inventories;

    public function __construct()
    {
        $this->Inventories = new ArrayCollection();
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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Inventory>
     */
    public function getInventories(): Collection
    {
        return $this->Inventories;
    }

    public function addInventory(Inventory $inventory): static
    {
        if (!$this->Inventories->contains($inventory)) {
            $this->Inventories->add($inventory);
            $inventory->setWareHouse($this);
        }

        return $this;
    }

    public function removeInventory(Inventory $inventory): static
    {
        if ($this->Inventories->removeElement($inventory)) {
            // set the owning side to null (unless already changed)
            if ($inventory->getWareHouse() === $this) {
                $inventory->setWareHouse(null);
            }
        }

        return $this;
    }


}
