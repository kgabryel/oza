<?php

namespace App\Controller\Api;

use App\Services\Condition\Condition;
use App\Services\Entity\QuickListPositionService;
use Closure;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class QuickListsPositionsController extends BaseController
{
    private QuickListPositionService $listPositionService;

    public function __construct(RequestStack $requestStack, QuickListPositionService $listPositionService)
    {
        parent::__construct($requestStack);
        $this->listPositionService = $listPositionService;
    }

    public function check(int $id): Response
    {
        return ($this->getCondition($id, fn() => $this->listPositionService->check()))();
    }

    private function getCondition(int $id, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, fn(): bool => $this->listPositionService->find($id));
    }

    public function destroy(int $id): Response
    {
        return ($this->getCondition($id, fn() => $this->listPositionService->remove()))();
    }

    public function uncheck(int $id): Response
    {
        return ($this->getCondition($id, fn() => $this->listPositionService->uncheck()))();
    }
}
