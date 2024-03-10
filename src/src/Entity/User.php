<?php

namespace App\Entity;

use App\Entity\QuickList\ClipboardPosition;
use App\Entity\QuickList\ClipboardPosition as QuickListClipboardPosition;
use App\Entity\QuickList\QuickList;
use App\Entity\ShoppingList\ClipboardPosition as ShoppingListClipboardPosition;
use App\Entity\ShoppingList\ShoppingList;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\Table(name="users")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\OneToMany(targetEntity=Alert::class, mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $alerts;
    /**
     * @ORM\OneToMany(targetEntity=ApiKey::class, mappedBy="user", orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $apiKeys;
    /**
     * @ORM\OneToMany(targetEntity=Brand::class, mappedBy="user", orphanRemoval=true)
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $brands;
    /**
     * @ORM\OneToMany(targetEntity=QuickListClipboardPosition::class, mappedBy="user", orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $clipboardPositions;
    /**
     * @ORM\Column(type="string", length=180)
     */
    private string $email;
    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private ?int $fbId;
    /**
     * @ORM\OneToMany(targetEntity=ProductsGroup::class, mappedBy="user")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $groups;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\OneToMany(targetEntity=ShoppingList::class, mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $lists;
    /**
     * @ORM\OneToMany(targetEntity=ShoppingListClipboardPosition::class, mappedBy="user", orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $listsClipboardPositions;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $name;
    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $notes;
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;
    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="user", orphanRemoval=true)
     */
    private Collection $photos;
    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="user")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $products;
    /**
     * @ORM\OneToMany(targetEntity=QuickList::class, mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $quickLists;
    /**
     * @ORM\Column(type="json")
     */
    private array $roles;
    /**
     * @ORM\OneToOne(targetEntity=Settings::class, mappedBy="user", cascade={"persist", "remove"}, fetch="EAGER")
     */
    private Settings $settings;
    /**
     * @ORM\OneToMany(targetEntity=Shopping::class, mappedBy="user")
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private Collection $shopping;
    /**
     * @ORM\OneToMany(targetEntity=Shop::class, mappedBy="user")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $shops;
    /**
     * @ORM\OneToMany(targetEntity=SupplyGroup::class, mappedBy="user", orphanRemoval=true)
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private Collection $supplyGroups;
    /**
     * @ORM\OneToMany(targetEntity=Unit::class, mappedBy="user")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private Collection $units;
    /**
     * @ORM\Column(type="integer")
     */
    private int $userType;

    public function __construct()
    {
        $this->roles = [];
        $this->fbId = null;
        $this->password = '';
        $this->userType = 1;
        $this->email = '';
        $this->units = new ArrayCollection();
        $this->quickLists = new ArrayCollection();
        $this->shops = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->alerts = new ArrayCollection();
        $this->lists = new ArrayCollection();
        $this->shopping = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->clipboardPositions = new ArrayCollection();
        $this->listsClipboardPositions = new ArrayCollection();
        $this->supplyGroups = new ArrayCollection();
        $this->apiKeys = new ArrayCollection();
        $this->brands = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->setUser($this);
        }

        return $this;
    }

    public function addApiKey(ApiKey $apiKey): self
    {
        if (!$this->apiKeys->contains($apiKey)) {
            $this->apiKeys[] = $apiKey;
            $apiKey->setUser($this);
        }

        return $this;
    }

    public function addBrand(Brand $brand): self
    {
        if (!$this->brands->contains($brand)) {
            $this->brands[] = $brand;
            $brand->setUser($this);
        }

        return $this;
    }

    public function addClipboardPosition(ClipboardPosition $clipboardPosition): self
    {
        if (!$this->clipboardPositions->contains($clipboardPosition)) {
            $this->clipboardPositions[] = $clipboardPosition;
            $clipboardPosition->setUser($this);
        }

        return $this;
    }

    public function addGroup(ProductsGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->setUser($this);
        }

        return $this;
    }

    public function addList(ShoppingList $list): self
    {
        if (!$this->lists->contains($list)) {
            $this->lists[] = $list;
            $list->setUser($this);
        }

        return $this;
    }

    public function addListsClipboardPosition(ShoppingListClipboardPosition $listsClipboardPosition): self
    {
        if (!$this->listsClipboardPositions->contains($listsClipboardPosition)) {
            $this->listsClipboardPositions[] = $listsClipboardPosition;
            $listsClipboardPosition->setUser($this);
        }

        return $this;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setUser($this);
        }

        return $this;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setUser($this);
        }

        return $this;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUser($this);
        }

        return $this;
    }

    public function addQuickList(QuickList $quickList): self
    {
        if (!$this->quickLists->contains($quickList)) {
            $this->quickLists[] = $quickList;
            $quickList->setUser($this);
        }

        return $this;
    }

    public function addShop(Shop $shop): self
    {
        if (!$this->shops->contains($shop)) {
            $this->shops[] = $shop;
            $shop->setUser($this);
        }

        return $this;
    }

    public function addShopping(Shopping $shopping): self
    {
        if (!$this->shopping->contains($shopping)) {
            $this->shopping[] = $shopping;
            $shopping->setUser($this);
        }

        return $this;
    }

    public function addSupplyGroup(SupplyGroup $supplyGroup): self
    {
        if (!$this->supplyGroups->contains($supplyGroup)) {
            $this->supplyGroups[] = $supplyGroup;
            $supplyGroup->setUser($this);
        }

        return $this;
    }

    public function addUnit(Unit $unit): self
    {
        if (!$this->units->contains($unit)) {
            $this->units[] = $unit;
            $unit->setUser($this);
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return Collection|Alert[]
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    /**
     * @return Collection|ApiKey[]
     */
    public function getApiKeys(): Collection
    {
        return $this->apiKeys;
    }

    /**
     * @return Collection<int, Brand>
     */
    public function getBrands(): Collection
    {
        return $this->brands;
    }

    /**
     * @return Collection|ClipboardPosition[]
     */
    public function getClipboardPositions(): Collection
    {
        return $this->clipboardPositions;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFbId(): ?int
    {
        return $this->fbId;
    }

    public function setFbId(?int $fbId): self
    {
        $this->fbId = $fbId;

        return $this;
    }

    /**
     * @return Collection|ProductsGroup[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|ShoppingList[]
     */
    public function getLists(): Collection
    {
        return $this->lists;
    }

    /**
     * @return Collection|ShoppingListClipboardPosition[]
     */
    public function getListsClipboardPositions(): Collection
    {
        return $this->listsClipboardPositions;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @return Collection|QuickList[]
     */
    public function getQuickLists(): Collection
    {
        return $this->quickLists;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    public function getSettings(): Settings
    {
        return $this->settings;
    }

    public function setSettings(Settings $settings): self
    {
        // set the owning side of the relation if necessary
        if ($settings->getUser() !== $this) {
            $settings->setUser($this);
        }
        $this->settings = $settings;

        return $this;
    }

    /**
     * @return Collection|Shopping[]
     */
    public function getShopping(): Collection
    {
        return $this->shopping;
    }

    /**
     * @return Collection|Shop[]
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    /**
     * @return Collection|SupplyGroup[]
     */
    public function getSupplyGroups(): Collection
    {
        return $this->supplyGroups;
    }

    /**
     * @return Collection|Unit[]
     */
    public function getUnits(): Collection
    {
        return $this->units;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUserType(): int
    {
        return $this->userType;
    }

    public function setUserType(int $userType): self
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        if ($this->userType === 2) {
            return ($this->name ?? '') === '' ? $this->email : $this->name ?? '';
        }

        return $this->email;
    }

    public function removeAlert(Alert $alert): self
    {
        $this->alerts->removeElement($alert);

        return $this;
    }

    public function removeApiKey(ApiKey $apiKey): self
    {
        $this->apiKeys->removeElement($apiKey);

        return $this;
    }

    public function removeBrand(Brand $brand): self
    {
        $this->brands->removeElement($brand);

        return $this;
    }

    public function removeClipboardPosition(ClipboardPosition $clipboardPosition): self
    {
        $this->clipboardPositions->removeElement($clipboardPosition);

        return $this;
    }

    public function removeGroup(ProductsGroup $group): self
    {
        $this->groups->removeElement($group);

        return $this;
    }

    public function removeList(ShoppingList $list): self
    {
        $this->lists->removeElement($list);

        return $this;
    }

    public function removeListsClipboardPosition(ShoppingListClipboardPosition $listsClipboardPosition): self
    {
        $this->listsClipboardPositions->removeElement($listsClipboardPosition);

        return $this;
    }

    public function removeNote(Note $note): self
    {
        $this->notes->removeElement($note);

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        $this->photos->removeElement($photo);

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }

    public function removeQuickList(QuickList $quickList): self
    {
        $this->quickLists->removeElement($quickList);

        return $this;
    }

    public function removeShop(Shop $shop): self
    {
        $this->shops->removeElement($shop);

        return $this;
    }

    public function removeShopping(Shopping $shopping): self
    {
        $this->shopping->removeElement($shopping);

        return $this;
    }

    public function removeSupplyGroup(SupplyGroup $supplyGroup): self
    {
        $this->supplyGroups->removeElement($supplyGroup);

        return $this;
    }

    public function removeUnit(Unit $unit): self
    {
        $this->units->removeElement($unit);

        return $this;
    }
}
