<?php

namespace App\Services\SettingsStoreAction;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Action
{
    protected FormInterface $form;
    protected Request $request;

    public function __construct(Request $request, FormInterface $form)
    {
        $this->form = $form;
        $this->request = $request;
    }

    abstract public function execute(): bool;

    abstract public function onSuccess(): Response;
}
