<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("products")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups("products")]
    private ?float $prix = null;

    #[ORM\Column]
    #[Groups("products")]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("products")]
    private ?\DateTimeInterface $dateExpiration = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[Groups("products")]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $productImage = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?User $productCreator = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $productType = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $Brand = null;

    #[ORM\Column]
    private ?int $serialNumber = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $shippingMethod = null;

    #[ORM\Column]
    #[Groups("products")]
    private ?float $shippingCost = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $handlingTime = null;

    #[ORM\Column(nullable: true)]
    #[Groups("products")]
    private ?array $shippingRegions = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'WhishList')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(\DateTimeInterface $dateExpiration): static
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getProductImage(): ?string
    {
        return $this->productImage;
    }

    public function setProductImage(string $productImage): static
    {
        $this->productImage = $productImage;

        return $this;
    }

    public function getProductCreator(): ?User
    {
        return $this->productCreator;
    }

    public function setProductCreator(?User $productCreator): static
    {
        $this->productCreator = $productCreator;

        return $this;
    }

    public function getProductType(): ?string
    {
        return $this->productType;
    }

    public function setProductType(string $productType): static
    {
        $this->productType = $productType;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->Brand;
    }

    public function setBrand(string $Brand): static
    {
        $this->Brand = $Brand;

        return $this;
    }

    public function getSerialNumber(): ?int
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(int $serialNumber): static
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getShippingMethod(): ?string
    {
        return $this->shippingMethod;
    }

    public function setShippingMethod(string $shippingMethod): static
    {
        $this->shippingMethod = $shippingMethod;

        return $this;
    }

    public function getShippingCost(): ?float
    {
        return $this->shippingCost;
    }

    public function setShippingCost(float $shippingCost): static
    {
        $this->shippingCost = $shippingCost;

        return $this;
    }

    public function getHandlingTime(): ?string
    {
        return $this->handlingTime;
    }

    public function setHandlingTime(string $handlingTime): static
    {
        $this->handlingTime = $handlingTime;

        return $this;
    }

    public function getShippingRegions(): ?array
    {
        return $this->shippingRegions;
    }

    public function setShippingRegions(?array $shippingRegions): static
    {
        $this->shippingRegions = $shippingRegions;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addWhishList($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeWhishList($this);
        }

        return $this;
    }



}
