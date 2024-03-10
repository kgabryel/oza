<?php

namespace App\Controller\Api;

use App\Services\Condition\Condition;
use App\Services\Entity\UnitService;
use App\Services\Transformer\UnitTransformer;
use Closure;
use Symfony\Component\HttpFoundation\Response;

final class UnitsController extends BaseController
{
    public function show(int $id, UnitService $unitService): Response
    {
        $condition = $this->getCondition(
            fn(): bool => $unitService->find($id),
            function() use ($unitService) {
                return UnitTransformer::toArray($unitService->getUnit()->getMain() ?? $unitService->getUnit());
            }
        );

        return $condition();
    }

    private function getCondition(Closure $condition, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, $condition);
    }
}
