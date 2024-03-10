<?php

namespace App\Services\Entity;

use App\Config\Message\AlertMessages;
use App\Controller\Web\BaseController;
use App\Entity\Supply;
use App\Entity\SupplyAlert;
use App\Repository\SupplyAlertRepository;
use App\Services\SupplyAlerts\SupplyAlertsService;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class SupplyAlertService extends EntityService
{
    private SupplyAlert $alert;
    private SupplyAlertRepository $alertRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        SupplyAlertRepository $alertRepository
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->alertRepository = $alertRepository;
    }

    public function find(int $id): bool
    {
        $alert = $this->alertRepository->findById($id, $this->user);

        if ($alert === null) {
            return false;
        }
        $this->alert = $alert;

        return true;
    }

    public function remove(SupplyAlertsService $alertsService): void
    {
        $alert = $this->alert->getAlert();
        $reactivate = $alert->isActive();
        $this->removeEntity($this->alert);
        if ($reactivate) {
            $alert->deactivate();
            $this->saveEntity($alert);
            $this->reactivate(null, $alertsService);
        }
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, AlertMessages::DELETE_CORRECT);
    }

    public function getAlert(): SupplyAlert
    {
        return $this->alert;
    }

    public function reactivate(?Supply $supply, SupplyAlertsService $alertsService): void
    {
        $supply ??= $this->alert->getSupply();
        /** @var SupplyAlert $supplyAlert */
        foreach ($supply->getAlerts() as $supplyAlert) {
            $alert = $supplyAlert->getAlert();
            $alert->deactivate();
            $this->entityManager->persist($alert);
        }
        $this->entityManager->flush();
        $alertsService->setAlerts($supply->getAlerts()->toArray());
        $alertsService->sort();
        $supplyAlert = $alertsService->getAlertToActivate($supply);
        if ($supplyAlert === null) {
            return;
        }
        $alert = $supplyAlert->getAlert();
        $alert->activate();
        $this->saveEntity($alert);
    }
}
