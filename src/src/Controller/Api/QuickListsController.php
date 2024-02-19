<?php

namespace App\Controller\Api;

use App\Services\Condition\Condition;
use App\Services\Entity\QuickListService;
use App\Services\Factory\QuickList\ClipboardPositionFactory;
use Closure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class QuickListsController extends BaseController
{
    public function destroy(
        int $id,
        QuickListService $quickListService,
        SessionInterface $session,
        ClipboardPositionFactory $clipboardPositionFactory
    ): Response {
        $condition = $this->getCondition(
            fn(): bool => $quickListService->find($id),
            fn() => $quickListService->remove($session, $clipboardPositionFactory)
        );

        return $condition();
    }

    private function getCondition(Closure $condition, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, $condition);
    }
}
