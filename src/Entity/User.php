<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: UserRepository::class)]
/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["users","products"])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Groups("users")]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    #[Assert\NotBlank]
    #[Groups(["users","rates"])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups("users")]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["users","rates"])]
    private ?string $LastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["users","rates"])]
    private ?string $FirstName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateOfBirth = null;

    #[ORM\Column]
    #[Groups("users")]
    private ?bool $Status = null;

    #[ORM\Column]
    #[Groups("users")]
    private ?int $Tel = null;

    #[ORM\Column(length: 255)]
    private ?string $VerificationToken = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["users","rates"])]
    private ?string $profileImage = null;

     /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="profileImage")
     * @var File|null
     */
    private ?File $imageFile = null;

    #[ORM\OneToMany(mappedBy: 'productCreator', targetEntity: Product::class)]
    private Collection $products;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'users')]
    #[Groups("users")]
    #[ORM\JoinTable(name: "user_wishlist_products")]
    private Collection $WhishList;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Rate::class)]
    private Collection $rates;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'LikedUsers')]
    #[ORM\JoinTable(name: "user_liked_products")]
    #[Groups(["users"])]
    private Collection $LikedProduct;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->WhishList = new ArrayCollection();
        $this->rates = new ArrayCollection();
        $this->LikedProduct = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): static
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(string $FirstName): static
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->DateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $DateOfBirth): static
    {
        $this->DateOfBirth = $DateOfBirth;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->Status;
    }

    public function setStatus(bool $Status): static
    {
        $this->Status = $Status;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->Tel;
    }

    public function setTel(int $Tel): static
    {
        $this->Tel = $Tel;

        return $this;
    }

    public function getVerificationToken(): ?string
    {
        return $this->VerificationToken;
    }

    public function setVerificationToken(string $VerificationToken): static
    {
        $this->VerificationToken = $VerificationToken;

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(string $profileImage): static
    {
        $this->profileImage = $profileImage;

        return $this;
    }
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setProductCreator($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProductCreator() === $this) {
                $product->setProductCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getWhishList(): Collection
    {
        return $this->WhishList;
    }

    public function addWhishList(Product $whishList): static
    {
        if (!$this->WhishList->contains($whishList)) {
            $this->WhishList->add($whishList);
        }

        return $this;
    }

    public function removeWhishList(Product $whishList): static
    {
        $this->WhishList->removeElement($whishList);

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
            $rate->setUser($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): static
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getUser() === $this) {
                $rate->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getLikedProduct(): Collection
    {
        return $this->LikedProduct;
    }

    public function addLikedProduct(Product $likedProduct): static
    {
        if (!$this->LikedProduct->contains($likedProduct)) {
            $this->LikedProduct->add($likedProduct);
        }

        return $this;
    }

    public function removeLikedProduct(Product $likedProduct): static
    {
        $this->LikedProduct->removeElement($likedProduct);

        return $this;
    }
}
