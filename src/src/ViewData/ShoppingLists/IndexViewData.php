<?php

namespace App\ViewData\ShoppingLists;

use App\Config\Settings;
use App\Config\ViewParameters;
use App\Entity\ShoppingList\ClipboardPosition;
use App\Entity\ShoppingList\ShoppingList;
use App\Repository\ShoppingList\ClipboardPositionRepository;
use App\Repository\ShoppingList\ListRepository;
use App\Services\Transformer\ShoppingListClipboardPositionTransformer;
use App\Services\Transformer\ShoppingListTransformer;
use App\ViewData\ViewData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class IndexViewData extends ViewData
{
    protected array $options;

    public function __construct(
        ListRepository $listRepository,
        TokenStorageInterface $tokenStorage,
        ClipboardPositionRepository $clipboardPositionRepository,
        SessionInterface $session
    ) {
        parent::__construct($session);
        $user = $tokenStorage->getToken()->getUser();
        $this->options[ViewParameters::HIDE] = $session->get(Settings::HIDE_BOUGHT);
        $this->options[ViewParameters::SHOPPING_LIST_LAYOUT] =
            $session->get(Settings::SHOOPING_LIST_LAYOUT_TYPE)->value;
        $this->options[ViewParameters::CLIPBOARD_POSITIONS] = array_map(
            static fn(ClipboardPosition $clipboardPosition): array => ShoppingListClipboardPositionTransformer::toArray(
                $clipboardPosition
            ),
            $clipboardPositionRepository->findBy(['user' => $user], ['id' => 'DESC'])
        );
        $this->options[ViewParameters::LISTS] = array_map(
            static fn(ShoppingList $shoppingList): array => ShoppingListTransformer::toArray($shoppingList),
            $listRepository->findBy(['user' => $user], ['createdAt' => 'DESC'])
        );
    }
}
