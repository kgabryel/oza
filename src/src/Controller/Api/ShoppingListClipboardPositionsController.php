<?php

namespace App\Controller\Api;

use App\Entity\ShoppingList\ClipboardPosition;
use App\Repository\ShoppingList\ClipboardPositionRepository;
use App\Services\Condition\Condition;
use App\Services\Entity\ShoppingListClipboardPositionService;
use App\Services\Transformer\ShoppingListClipboardPositionTransformer;
use Closure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ShoppingListClipboardPositionsController extends BaseController
{
    public function destroy(int $id, ShoppingListClipboardPositionService $clipboardPositionService): Response
    {
        $condition = $this->getCondition(
            fn(): bool => $clipboardPositionService->find($id),
            fn() => $clipboardPositionService->remove()
        );

        return $condition();
    }

    private function getCondition(Closure $condition, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, $condition);
    }

    public function index(ClipboardPositionRepository $clipboardPositionRepository): Response
    {
        return new JsonResponse(
            array_map(
                static fn(ClipboardPosition $clipboardPosition
                ): array => ShoppingListClipboardPositionTransformer::toArray($clipboardPosition),
                $clipboardPositionRepository->findBy(['user' => $this->getUser()], ['id' => 'DESC'])
            )
        );
    }
}
