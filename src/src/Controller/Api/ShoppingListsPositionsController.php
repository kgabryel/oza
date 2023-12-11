<?php

namespace App\Controller\Api;

use App\Form\ChangeShopForm;
use App\Repository\ShoppingList\PositionRepository;
use App\Services\Condition\Condition;
use App\Services\Entity\ShoppingListPositionService;
use App\Services\ShoppingListPrices\Position\PriceService;
use App\Services\Transformer\ShoppingListPositionTransformer;
use Closure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

final class ShoppingListsPositionsController extends BaseController
{
    private ShoppingListPositionService $listPositionService;

    public function __construct(RequestStack $requestStack, ShoppingListPositionService $listPositionService)
    {
        parent::__construct($requestStack);
        $this->listPositionService = $listPositionService;
    }

    public function changeShop(int $id): Response
    {
        $condition = $this->getCondition(
            $id,
            function() {
                $form = $this->createForm(ChangeShopForm::class, null, [
                    'method' => Request::METHOD_PATCH
                ]);
                if (!$this->listPositionService->changeShop($form, $this->request)) {
                    return new Response(null, Response::HTTP_BAD_REQUEST);
                }

                return ShoppingListPositionTransformer::toArray($this->listPositionService->getPosition());
            }
        );

        return $condition();
    }

    private function getCondition(int $id, Closure $successAction): Condition
    {
        return $this->getBaseCondition($successAction, fn(): bool => $this->listPositionService->find($id));
    }

    public function check(int $id): Response
    {
        return ($this->getCondition($id, fn() => $this->listPositionService->check()))();
    }

    public function destroy(int $id): Response
    {
        return ($this->getCondition($id, fn() => $this->listPositionService->remove()))();
    }

    public function getPrices(int $id, PositionRepository $positionRepository, PriceService $priceService): Response
    {
        $position = $positionRepository->findById($id, $this->getUser());
        if ($position === null) {
            return new Response(null, Response::HTTP_FORBIDDEN);
        }
        $priceService->setPosition($position)->findPrices();

        return new JsonResponse($priceService->getShops());
    }

    public function uncheck(int $id): Response
    {
        return ($this->getCondition($id, fn() => $this->listPositionService->uncheck()))();
    }
}
