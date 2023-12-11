<?php

namespace App\ViewData\ShoppingLists;

use App\Config\ViewParameters;
use App\Entity\ShoppingList\ClipboardPosition;
use App\Repository\ProductRepository;
use App\Repository\ProductsGroupRepository;
use App\Repository\ShoppingList\ClipboardPositionRepository;
use App\Services\Transformer\ShoppingListClipboardPositionTransformer;
use App\Utils\FormUtils;
use App\ViewData\ViewData;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FormViewData extends ViewData
{
    public function __construct(
        ProductsGroupRepository $productsGroupRepository,
        ProductRepository $productRepository,
        TokenStorageInterface $tokenStorage,
        ClipboardPositionRepository $clipboardPositionRepository,
        SessionInterface $session
    ) {
        parent::__construct($session);
        $user = $tokenStorage->getToken()->getUser();
        $this->options[ViewParameters::PRODUCTS_GROUPS] = FormUtils::productsGroupSelectOptions(
            $productsGroupRepository->findBy(['user' => $user], ['id' => 'DESC'])
        );
        $this->options[ViewParameters::PRODUCTS] = FormUtils::productSelectOptions(
            $productRepository->findBy(['user' => $user], ['id' => 'DESC'])
        );
        $this->options[ViewParameters::CLIPBOARD_POSITIONS] = array_map(
            static fn(ClipboardPosition $clipboardPosition
            ): array => ShoppingListClipboardPositionTransformer::toArray($clipboardPosition),
            $clipboardPositionRepository->findBy(['user' => $user], ['id' => 'DESC'])
        );
        $this->options[ViewParameters::ADD_EMPTY_POSITION] = false;
    }

    public function addEmptyPosition(): self
    {
        $this->options[ViewParameters::ADD_EMPTY_POSITION] = true;

        return $this;
    }

    public function addForm(FormInterface $form): self
    {
        $this->options[ViewParameters::FORM] = $form->createView();

        return $this;
    }

    public function addId(int $id): self
    {
        $this->options[ViewParameters::ID] = $id;

        return $this;
    }
}
