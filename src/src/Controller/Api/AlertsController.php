<?php

namespace App\Controller\Api;

use App\Repository\AlertRepository;
use App\Services\Condition\Condition;
use App\Services\Entity\AlertService;
use Closure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class AlertsController extends BaseController
{
    private AlertRepository $alertRepository;
    private AlertService $alertService;

    public function __construct(
        AlertRepository $alertRepository,
        AlertService $alertService,
        RequestStack $requestStack
    ) {
        parent::__construct($requestStack);
        $this->alertRepository = $alertRepository;
        $this->alertService = $alertService;
    }

    public function activate(int $id): Response
    {
        return $this->changeAlertStatus($id, true);
    }

    private function changeAlertStatus(int $id, bool $status): Response
    {
        return ($this->getCondition($id, fn() => $this->alertService->changeStatus($status)))();
    }

    private function getCondition(int $id, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, fn(): bool => $this->alertService->find($id));
    }

    public function deactivate(int $id): Response
    {
        return $this->changeAlertStatus($id, false);
    }

    public function getAlerts(): Response
    {
        return new JsonResponse($this->alertRepository->getActiveAlerts($this->getUser()));
    }
}
