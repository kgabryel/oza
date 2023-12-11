<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingsRepository::class)
 * @ORM\Table(name="settings")
 */
class Settings
{
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $autocomplete;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $createSupply;
    /**
     * @ORM\Column(type="integer")
     */
    private int $deleteListDays;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $deleteLists;
    /**
     * @ORM\Column(type="integer")
     */
    private int $deleteQuickListDays;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $deleteQuickLists;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $deleteUncheckedPositions;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $deleteUncheckedPositionsQuick;
    /**
     * @ORM\Column(type="boolean")
     */
    private bool $hideBought;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="integer")
     */
    private int $maxShopsGroupCount;
    /**
     * @ORM\Column(type="integer")
     */
    private int $newShoppingDays;
    /**
     * @ORM\Column(type="integer")
     */
    private int $pagination;
    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="settings", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $gridLayout;

    public function getAutocomplete(): bool
    {
        return $this->autocomplete;
    }

    public function setAutocomplete(bool $autocomplete): self
    {
        $this->autocomplete = $autocomplete;

        return $this;
    }

    public function getCreateSupply(): bool
    {
        return $this->createSupply;
    }

    public function setCreateSupply(bool $createSupply): self
    {
        $this->createSupply = $createSupply;

        return $this;
    }

    public function getDeleteListDays(): int
    {
        return $this->deleteListDays;
    }

    public function setDeleteListDays(int $deleteListDays): self
    {
        $this->deleteListDays = $deleteListDays;

        return $this;
    }

    public function getDeleteLists(): bool
    {
        return $this->deleteLists;
    }

    public function setDeleteLists(bool $deleteLists): self
    {
        $this->deleteLists = $deleteLists;

        return $this;
    }

    public function getDeleteQuickListDays(): int
    {
        return $this->deleteQuickListDays;
    }

    public function setDeleteQuickListDays(int $deleteQuickListDays): self
    {
        $this->deleteQuickListDays = $deleteQuickListDays;

        return $this;
    }

    public function getDeleteQuickLists(): bool
    {
        return $this->deleteQuickLists;
    }

    public function setDeleteQuickLists(bool $deleteQuickLists): self
    {
        $this->deleteQuickLists = $deleteQuickLists;

        return $this;
    }

    public function getDeleteUncheckedPositions(): bool
    {
        return $this->deleteUncheckedPositions;
    }

    public function setDeleteUncheckedPositions(bool $deleteUncheckedPositions): self
    {
        $this->deleteUncheckedPositions = $deleteUncheckedPositions;

        return $this;
    }

    public function getDeleteUncheckedPositionsQuick(): bool
    {
        return $this->deleteUncheckedPositionsQuick;
    }

    public function setDeleteUncheckedPositionsQuick(bool $deleteUncheckedPositionsQuick): self
    {
        $this->deleteUncheckedPositionsQuick = $deleteUncheckedPositionsQuick;

        return $this;
    }

    public function getHideBought(): bool
    {
        return $this->hideBought;
    }

    public function setHideBought(bool $hideBought): self
    {
        $this->hideBought = $hideBought;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMaxShopsGroupCount(): int
    {
        return $this->maxShopsGroupCount;
    }

    public function setMaxShopsGroupCount(int $maxShopsGroupCount): self
    {
        $this->maxShopsGroupCount = $maxShopsGroupCount;

        return $this;
    }

    public function getNewShoppingDays(): int
    {
        return $this->newShoppingDays;
    }

    public function setNewShoppingDays(int $newShoppingDays): self
    {
        $this->newShoppingDays = $newShoppingDays;

        return $this;
    }

    public function getPagination(): int
    {
        return $this->pagination;
    }

    public function setPagination(int $pagination): self
    {
        $this->pagination = $pagination;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isGridLayout(): ?bool
    {
        return $this->gridLayout;
    }

    public function setGridLayout(bool $gridLayout): self
    {
        $this->gridLayout = $gridLayout;

        return $this;
    }
}
