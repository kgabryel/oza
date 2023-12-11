<?php

namespace App\Controller\Api;

use App\Entity\Supply;
use App\Repository\SupplyRepository;
use App\Services\Condition\Condition;
use App\Services\Entity\SupplyService;
use App\Services\Transformer\SupplyTransformer;
use Closure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SuppliesController extends BaseController
{
    public function index(SupplyRepository $supplyRepository): Response
    {
        $supplies = array_map(
            static fn(Supply $supply): array => SupplyTransformer::toArray($supply),
            $supplyRepository->findForUser($this->getUser())
        );
        usort($supplies, static fn($a, $b): int => strcmp($a['group']['name'], $b['group']['name']));

        return new JsonResponse($supplies);
    }

    public function show(int $id, SupplyService $supplyService): Response
    {
        $condition = $this->getCondition(
            fn(): bool => $supplyService->find($id),
            fn(): array => SupplyTransformer::toArray($supplyService->getSupply())
        );

        return $condition();
    }

    private function getCondition(Closure $condition, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, $condition);
    }
}
