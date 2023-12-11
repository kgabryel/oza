<?php

namespace App\Model\Form;

use App\Config\Settings as SettingsConfig;
use App\Config\ShoppingListLayoutType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Settings
{
    private ?bool $autocomplete;
    private ?bool $createSupply;
    private ?int $deleteListDays;
    private ?bool $deleteLists;
    private ?int $deleteQuickListDays;
    private ?bool $deleteQuickLists;
    private ?bool $deleteUncheckedPositions;
    private ?bool $deleteUncheckedPositionsQuick;
    private ?bool $hideBought;
    private ?int $maxShopsGroupCount;
    private ?int $newShoppingDays;
    private ?int $paginationCount;
    private ?ShoppingListLayoutType $shoppingListLayoutType;

    public function __construct()
    {
        $this->hideBought = null;
        $this->paginationCount = null;
        $this->deleteUncheckedPositions = null;
        $this->deleteUncheckedPositionsQuick = null;
        $this->maxShopsGroupCount = null;
        $this->newShoppingDays = null;
        $this->createSupply = null;
        $this->deleteLists = null;
        $this->deleteListDays = null;
        $this->deleteQuickLists = null;
        $this->deleteQuickListDays = null;
        $this->autocomplete = null;
        $this->shoppingListLayoutType = null;
    }

    public static function fromSession(SessionInterface $session): self
    {
        $setting = new self();
        $setting->setHideBought($session->get(SettingsConfig::HIDE_BOUGHT));
        $setting->setPaginationCount($session->get(SettingsConfig::PAGINATION_COUNT));
        $setting->setDeleteUncheckedPositions($session->get(SettingsConfig::DELETE_UNCHECKED_POSITIONS));
        $setting->setMaxShopsGroupCount($session->get(SettingsConfig::MAX_SHOPS_GROUP_COUNT));
        $setting->setNewShoppingDays($session->get(SettingsConfig::NEW_SHOPPING_DAYS));
        $setting->setDeleteUncheckedPositionsQuick(
            $session->get(SettingsConfig::DELETE_UNCHECKED_POSITIONS_QUICK)
        );
        $setting->setCreateSupply($session->get(SettingsConfig::CREATE_SUPPLY));
        $setting->setDeleteLists($session->get(SettingsConfig::DELETE_LISTS));
        $setting->setDeleteListDays($session->get(SettingsConfig::DELETE_LIST_DAYS));
        $setting->setDeleteQuickLists($session->get(SettingsConfig::DELETE_QUICK_LISTS));
        $setting->setDeleteQuickListDays($session->get(SettingsConfig::DELETE_QUICK_LIST_DAYS));
        $setting->setAutocomplete($session->get(SettingsConfig::AUTOCOMPLETE));
        $setting->setShoppingListLayoutType($session->get(SettingsConfig::SHOOPING_LIST_LAYOUT_TYPE));

        return $setting;
    }

    public function getShoppingListLayoutType(): ?ShoppingListLayoutType
    {
        return $this->shoppingListLayoutType;
    }

    public function setShoppingListLayoutType(?ShoppingListLayoutType $shoppingListLayoutType): void
    {
        $this->shoppingListLayoutType = $shoppingListLayoutType;
    }

    public function getAutocomplete(): ?bool
    {
        return $this->autocomplete;
    }

    public function setAutocomplete(?bool $autocomplete): void
    {
        $this->autocomplete = $autocomplete;
    }

    public function getCreateSupply(): ?bool
    {
        return $this->createSupply;
    }

    public function setCreateSupply(?bool $createSupply): void
    {
        $this->createSupply = $createSupply;
    }

    public function getDeleteListDays(): ?int
    {
        return $this->deleteListDays;
    }

    public function setDeleteListDays(?int $deleteListDays): void
    {
        $this->deleteListDays = $deleteListDays;
    }

    public function getDeleteLists(): ?bool
    {
        return $this->deleteLists;
    }

    public function setDeleteLists(?bool $deleteLists): void
    {
        $this->deleteLists = $deleteLists;
    }

    public function getDeleteQuickListDays(): ?int
    {
        return $this->deleteQuickListDays;
    }

    public function setDeleteQuickListDays(?int $deleteQuickListDays): void
    {
        $this->deleteQuickListDays = $deleteQuickListDays;
    }

    public function getDeleteQuickLists(): ?bool
    {
        return $this->deleteQuickLists;
    }

    public function setDeleteQuickLists(?bool $deleteQuickLists): void
    {
        $this->deleteQuickLists = $deleteQuickLists;
    }

    public function getDeleteUncheckedPositions(): ?bool
    {
        return $this->deleteUncheckedPositions;
    }

    public function setDeleteUncheckedPositions(?bool $deleteUncheckedPositions): void
    {
        $this->deleteUncheckedPositions = $deleteUncheckedPositions;
    }

    public function getDeleteUncheckedPositionsQuick(): ?bool
    {
        return $this->deleteUncheckedPositionsQuick;
    }

    public function setDeleteUncheckedPositionsQuick(?bool $deleteUncheckedPositionsQuick): void
    {
        $this->deleteUncheckedPositionsQuick = $deleteUncheckedPositionsQuick;
    }

    public function getHideBought(): ?bool
    {
        return $this->hideBought;
    }

    public function setHideBought(?bool $hideBought): void
    {
        $this->hideBought = $hideBought;
    }

    public function getMaxShopsGroupCount(): ?int
    {
        return $this->maxShopsGroupCount;
    }

    public function setMaxShopsGroupCount(?int $maxShopsGroupCount): void
    {
        $this->maxShopsGroupCount = $maxShopsGroupCount;
    }

    public function getNewShoppingDays(): ?int
    {
        return $this->newShoppingDays;
    }

    public function setNewShoppingDays(?int $newShoppingDays): void
    {
        $this->newShoppingDays = $newShoppingDays;
    }

    public function getPaginationCount(): ?int
    {
        return $this->paginationCount;
    }

    public function setPaginationCount(?int $paginationCount): void
    {
        $this->paginationCount = $paginationCount;
    }
}
