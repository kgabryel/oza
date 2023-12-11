<?php

namespace App\ViewData\Alerts;

use App\Config\ViewParameters;
use App\Entity\Alert;
use App\Services\SupplyAlerts\SupplyAlertsService;
use App\ViewData\ViewData;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EditViewData extends ViewData
{
    private SupplyAlertsService $alertsService;

    public function __construct(SupplyAlertsService $alertsService, SessionInterface $session)
    {
        parent::__construct($session);
        $this->alertsService = $alertsService;
    }

    public function addEditForm(FormInterface $form): self
    {
        $this->options[ViewParameters::FORM] = $form->createView();

        return $this;
    }

    public function addEntity(Alert $alert): self
    {
        $this->alertsService->setAlerts($alert->getSupplyAlerts()->toArray());
        $this->alertsService->sort();
        $this->options[ViewParameters::ENTITY] = $alert;
        $this->options[ViewParameters::ALERTS] = $this->alertsService->getAlerts();

        return $this;
    }
}
