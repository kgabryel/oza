<?php

namespace App\ViewData\Settings;

use App\Config\ViewParameters;
use App\Entity\User;
use App\Repository\SupplyRepository;
use App\ViewData\ViewData;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IndexViewData extends ViewData
{
    public function __construct(
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        SupplyRepository $supplyRepository
    ) {
        parent::__construct($session);
        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();
        $this->options[ViewParameters::DESCRIPTION_FORM] = [];
        $this->options[ViewParameters::REPORT_AVAILABLE] = $supplyRepository->findForUser($user) !== [];
        $this->options[ViewParameters::RESET_AVAILABLE] = $user->getFbId() === null;
    }

    public function addChangePasswordForm(FormInterface $form): self
    {
        $this->options[ViewParameters::CHANGE_PASSWORD_FORM] = $form->createView();

        return $this;
    }

    public function addDescriptionForm(FormInterface $form, int $id): self
    {
        $this->options[ViewParameters::DESCRIPTION_FORM][$id] = $form->createView();

        return $this;
    }

    public function addKeyForm(FormInterface $form): self
    {
        $this->options[ViewParameters::KEY_FORM] = $form->createView();

        return $this;
    }

    public function addKeys(Collection $keys): self
    {
        $this->options[ViewParameters::KEYS] = $keys;

        return $this;
    }

    public function addSettingsForm(FormInterface $form): self
    {
        $this->options[ViewParameters::SETTINGS_FORM] = $form->createView();

        return $this;
    }
}
