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
    #[Groups(["users", "products"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["users", "products"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["users", "products"])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(["users", "products"])]
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
    #[Groups(["users", "products"])]
    private ?string $productImage = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[Groups("products")]
    private ?User $productCreator = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $productType = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $Brand = null;

    #[ORM\Column(nullable: true)]
    private ?array $serialNumber = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $shippingMethod = null;

    #[ORM\Column]
    #[Groups(["users", "products"])]
    private ?float $shippingCost = null;

    #[ORM\Column(length: 255)]
    #[Groups("products")]
    private ?string $handlingTime = null;

    #[ORM\Column(nullable: true)]
    #[Groups("products")]
    private ?array $shippingRegions = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'WhishList')]
    #[Groups("products")]
    #[ORM\JoinTable(name: "user_wishlist_products")]
    private Collection $users;

    #[ORM\Column(nullable: true)]
    #[Groups("products")]
    private ?array $Features = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Rate::class)]
    private Collection $rates;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'LikedProduct')]
    #[ORM\JoinTable(name: "user_liked_products")]
    private Collection $LikedUsers;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Inventory::class)]
    #[Groups("users")]
    private Collection $Inventories;



    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->LikedUsers = new ArrayCollection();
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

    public function getSerialNumber(): ?array
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(array $serialNumber): static
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

    public function getFeatures(): ?array
    {
        return $this->Features;
    }

    public function setFeatures(?array $Features): static
    {
        $this->Features = $Features;

        return $this;
    }

    /**
     * @return Collection<int, Rate>
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): static
    {
        if (!$this->rates->contains($rate)) {
            $this->rates->add($rate);
            $rate->setProduct($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): static
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getProduct() === $this) {
                $rate->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLikedUsers(): Collection
    {
        return $this->LikedUsers;
    }

    public function addLikedUser(User $likedUser): static
    {
        if (!$this->LikedUsers->contains($likedUser)) {
            $this->LikedUsers->add($likedUser);
            $likedUser->addLikedProduct($this);
        }

        return $this;
    }

    public function removeLikedUser(User $likedUser): static
    {
        if ($this->LikedUsers->removeElement($likedUser)) {
            $likedUser->removeLikedProduct($this);
        }

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
            $inventory->setProduct($this);
        }

        return $this;
    }

    public function removeInventory(Inventory $inventory): static
    {
        if ($this->Inventories->removeElement($inventory)) {
            // set the owning side to null (unless already changed)
            if ($inventory->getProduct() === $this) {
                $inventory->setProduct(null);
            }
        }

        return $this;
    }


}
