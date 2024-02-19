<?php

namespace App\Services\Factory;

use App\Entity\Settings;
use App\Entity\User;

abstract class SettingsFactory
{
    public static function get(User $user): Settings
    {
        $settings = new Settings();
        $settings->setDeleteUncheckedPositionsQuick(false);
        $settings->setDeleteUncheckedPositions(false);
        $settings->setHideBought(false);
        $settings->setPagination(10);
        $settings->setNewShoppingDays(30);
        $settings->setMaxShopsGroupCount(3);
        $settings->setCreateSupply(false);
        $settings->setDeleteLists(false);
        $settings->setDeleteQuickLists(false);
        $settings->setDeleteListDays(30);
        $settings->setDeleteQuickListDays(30);
        $settings->setAutocomplete(true);
        $settings->setGridLayout(false);
        $settings->setUser($user);

        return $settings;
    }
}
