<?php

namespace App\Controller\Api;

use App\Entity\QuickList\ClipboardPosition;
use App\Repository\QuickList\ClipboardPositionRepository;
use App\Services\Condition\Condition;
use App\Services\Entity\QuickListClipboardPositionService;
use App\Services\Transformer\QuickListClipboardPositionTransformer;
use Closure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class QuickListClipboardPositionsController extends BaseController
{
    public function destroy(int $id, QuickListClipboardPositionService $clipboardPositionService): Response
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
                ): array => QuickListClipboardPositionTransformer::toArray($clipboardPosition),
                $clipboardPositionRepository->findBy(['user' => $this->getUser()], ['id' => 'DESC'])
            )
        );
    }
}
