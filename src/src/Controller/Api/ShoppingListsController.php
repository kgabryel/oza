<?php

namespace App\Controller\Api;

use App\Repository\ShoppingList\ListRepository;
use App\Services\Condition\Condition;
use App\Services\Entity\ShoppingListService;
use App\Services\Factory\ShoppingList\ClipboardPositionFactory;
use App\Services\ShoppingListPrices\List\PriceService;
use Closure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class ShoppingListsController extends BaseController
{
    public function destroy(
        int $id,
        ShoppingListService $productsListService,
        SessionInterface $session,
        ClipboardPositionFactory $clipboardPositionFactory
    ): Response {
        $condition = $this->getCondition(
            fn(): bool => $productsListService->find($id),
            fn() => $productsListService->remove($session, $clipboardPositionFactory)
        );

        return $condition();
    }

    private function getCondition(Closure $condition, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, $condition);
    }

    public function getPrices(int $id, PriceService $priceService, ListRepository $listRepository): Response
    {
        $list = $listRepository->findById($id, $this->getUser());
        if ($list === null) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $sets = $priceService->setList($list)->setPrices()->setSets()->getSets();

        return new JsonResponse($sets);
    }
}
