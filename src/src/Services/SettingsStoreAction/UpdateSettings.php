<?php

namespace App\Services\SettingsStoreAction;

use App\Services\SettingsService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateSettings extends Action
{
    private SettingsService $settingsService;

    public function __construct(Request $request, FormInterface $form, SettingsService $settingsService)
    {
        parent::__construct($request, $form);
        $this->settingsService = $settingsService;
    }

    public function execute(): bool
    {
        return $this->settingsService->update($this->form, $this->request);
    }

    public function onSuccess(): Response
    {
        return new RedirectResponse($this->request->headers->get('referer'), 302);
    }
}
