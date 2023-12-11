<?php

namespace App\Controller\Api;

use App\Services\Condition\Condition;
use App\Services\Entity\BrandService;
use App\Services\Transformer\BrandTransformer;
use Closure;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class BrandsController extends BaseController
{
    private BrandService $brandService;

    public function __construct(RequestStack $requestStack, BrandService $brandService)
    {
        parent::__construct($requestStack);
        $this->brandService = $brandService;
    }

    public function show(int $id): Response
    {
        $condition = $this->getCondition(
            $id,
            fn(): array => BrandTransformer::toArray($this->brandService->getBrand())
        );

        return $condition();
    }

    private function getCondition(int $id, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, fn(): bool => $this->brandService->find($id));
    }
}
