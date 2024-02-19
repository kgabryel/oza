<?php

namespace App\Controller\Api;

use App\Services\Condition\Condition;
use App\Services\Entity\ApiKeyService;
use Closure;
use Symfony\Component\HttpFoundation\Response;

final class SettingsController extends BaseController
{
    public function switch(int $id, ApiKeyService $apiKeyService): Response
    {
        $condition = $this->getCondition(fn(): bool => $apiKeyService->find($id), fn() => $apiKeyService->switch());

        return $condition();
    }

    private function getCondition(Closure $condition, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, $condition);
    }
}
