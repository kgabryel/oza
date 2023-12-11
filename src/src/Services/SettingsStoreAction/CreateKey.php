<?php

namespace App\Services\SettingsStoreAction;

use App\Services\Factory\ApiKeyFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateKey extends Action
{
    private ApiKeyFactory $apiKeyFactory;

    public function __construct(Request $request, FormInterface $form, ApiKeyFactory $apiKeyFactory)
    {
        parent::__construct($request, $form);
        $this->apiKeyFactory = $apiKeyFactory;
    }

    public function execute(): bool
    {
        return $this->apiKeyFactory->create($this->form, $this->request);
    }

    public function onSuccess(): Response
    {
        return new RedirectResponse($this->request->headers->get('referer'), 302);
    }
}
