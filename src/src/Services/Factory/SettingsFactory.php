<?php

namespace App\Services\Factory;

use App\Entity\Settings;
use App\Entity\User;

abstract class SettingsFactory
{
    public static function get(User $user): Settings
    {
        $settings = new Settings();
        $settings->setDeleteUncheckedPositionsQuick(false)
            ->setDeleteUncheckedPositions(false)
            ->setHideBought(false)
            ->setPagination(10)
            ->setNewShoppingDays(30)
            ->setMaxShopsGroupCount(3)
            ->setCreateSupply(false)
            ->setDeleteLists(false)
            ->setDeleteQuickLists(false)
            ->setDeleteListDays(30)
            ->setDeleteQuickListDays(30)
            ->setAutocomplete(true)
            ->setGridLayout(false)
            ->setUser($user);

        return $settings;
    }
}
