<?php

namespace App\Controller\Api;

use App\Services\Condition\Condition;
use App\Services\Entity\SupplyGroupService;
use App\Services\Transformer\SupplyGroupTransformer;
use Closure;
use Symfony\Component\HttpFoundation\Response;

final class SupplyGroupsController extends BaseController
{
    public function show(int $id, SupplyGroupService $supplyGroupService): Response
    {
        $condition = $this->getCondition(
            fn(): bool => $supplyGroupService->find($id),
            fn(): array => SupplyGroupTransformer::toArray($supplyGroupService->getSupplyGroup())
        );

        return $condition();
    }

    private function getCondition(Closure $condition, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, $condition);
    }
}
