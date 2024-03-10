<?php

namespace App\Services;

use App\Config\Message\SettingsMessages;
use App\Config\Settings as SettingsConfig;
use App\Config\ShoppingListLayoutType;
use App\Controller\Web\BaseController;
use App\Entity\Settings;
use App\Entity\User;
use App\Model\Form\Settings as SettingsModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SettingsService
{
    private EntityManagerInterface $entityManager;
    private FlashBagInterface $flashBag;
    private SessionInterface $session;
    private User $user;

    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag,
        UserService $userService
    ) {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->user = $userService->getUser();
    }

    public function changePassword(
        FormInterface $form,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher
    ): bool {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        $password = $form->getData()->getNewPassword();

        $this->user->setPassword($userPasswordHasher->hashPassword($this->user, $password));
        $this->entityManager->persist($this->user);
        $this->entityManager->flush();
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SettingsMessages::CHANGED_CORRECTLY);

        return true;
    }

    public function update(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var SettingsModel $data */
        $data = $form->getData();
        $settings = $this->user->getSettings();
        $settings->setHideBought($data->getHideBought());
        $settings->setPagination($data->getPaginationCount());
        $settings->setDeleteUncheckedPositions($data->getDeleteUncheckedPositions());
        $settings->setDeleteUncheckedPositionsQuick($data->getDeleteUncheckedPositionsQuick());
        $settings->setMaxShopsGroupCount($data->getMaxShopsGroupCount());
        $settings->setNewShoppingDays($data->getNewShoppingDays());
        $settings->setCreateSupply($data->getCreateSupply());
        $settings->setDeleteLists($data->getDeleteLists());
        $settings->setDeleteListDays($data->getDeleteListDays());
        $settings->setDeleteQuickLists($data->getDeleteQuickLists());
        $settings->setDeleteQuickListDays($data->getDeleteQuickListDays());
        $settings->setAutocomplete($data->getAutocomplete());
        $settings->setGridLayout($data->getShoppingListLayoutType() === ShoppingListLayoutType::GRID);
        $this->entityManager->persist($settings);
        $this->entityManager->flush();
        self::updateSession($this->session, $settings);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SettingsMessages::UPDATED_CORRECTLY);

        return true;
    }

    public static function updateSession(SessionInterface $session, Settings $settings): void
    {
        $session->set(SettingsConfig::HIDE_BOUGHT, $settings->getHideBought());
        $session->set(SettingsConfig::PAGINATION_COUNT, $settings->getPagination());
        $session->set(
            SettingsConfig::DELETE_UNCHECKED_POSITIONS,
            $settings->getDeleteUncheckedPositions()
        );
        $session->set(SettingsConfig::MAX_SHOPS_GROUP_COUNT, $settings->getMaxShopsGroupCount());
        $session->set(SettingsConfig::NEW_SHOPPING_DAYS, $settings->getNewShoppingDays());
        $session->set(
            SettingsConfig::DELETE_UNCHECKED_POSITIONS_QUICK,
            $settings->getDeleteUncheckedPositionsQuick()
        );
        $session->set(
            SettingsConfig::CREATE_SUPPLY,
            $settings->getCreateSupply()
        );
        $session->set(SettingsConfig::DELETE_LISTS, $settings->getDeleteLists());
        $session->set(SettingsConfig::DELETE_LIST_DAYS, $settings->getDeleteListDays());
        $session->set(SettingsConfig::DELETE_QUICK_LISTS, $settings->getDeleteQuickLists());
        $session->set(SettingsConfig::DELETE_QUICK_LIST_DAYS, $settings->getDeleteQuickListDays());
        $session->set(SettingsConfig::AUTOCOMPLETE, $settings->getAutocomplete());
        $session->set(
            SettingsConfig::SHOOPING_LIST_LAYOUT_TYPE,
            $settings->isGridLayout() ? ShoppingListLayoutType::GRID : ShoppingListLayoutType::LIST
        );
    }
}
