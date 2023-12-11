<?php

namespace App\ViewData\Supplies;

use App\Config\Settings;
use App\Config\ViewParameters;
use App\Entity\Supply;
use App\Entity\SupplyPart;
use App\Services\SupplyAlerts\SupplyAlertsService;
use App\Services\Transformer\SupplyPartTransformer;
use App\ViewData\ViewData;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EditViewData extends ViewData
{
    private SupplyAlertsService $alertsService;

    public function __construct(SessionInterface $session, SupplyAlertsService $alertsService)
    {
        parent::__construct($session);
        $this->options[ViewParameters::LIMIT] = $session->get(Settings::PAGINATION_COUNT);
        $this->alertsService = $alertsService;
    }

    public function addAlertForm(FormInterface $form): self
    {
        $this->options[ViewParameters::ALERT_FORM] = $form->createView();

        return $this;
    }

    public function addEditForm(FormInterface $form): self
    {
        $this->options[ViewParameters::EDIT_FORM] = $form->createView();

        return $this;
    }

    public function addEntity(Supply $supply): self
    {
        $this->alertsService->setAlerts($supply->getAlerts()->toArray());
        $this->alertsService->sort();
        $this->options[ViewParameters::ENTITY] = $supply;
        $this->options[ViewParameters::SUPPLIES] = array_map(
            static fn(SupplyPart $supplyPart): array => SupplyPartTransformer::toArray($supplyPart),
            $supply->getSupplyParts()->toArray()
        );
        $this->options[ViewParameters::ALERTS] = $this->alertsService->getAlerts();

        return $this;
    }

    public function addSupplyPartForm(FormInterface $form): self
    {
        $this->options[ViewParameters::SUPPLY_PART_FORM] = $form->createView();

        return $this;
    }
}
