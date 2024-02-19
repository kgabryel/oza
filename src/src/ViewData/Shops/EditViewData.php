<?php

namespace App\ViewData\Shops;

use App\Config\Settings;
use App\Config\ViewParameters;
use App\Entity\Shop;
use App\Entity\Shopping;
use App\Repository\ShoppingRepository;
use App\Services\Transformer\ShoppingTransformer;
use App\ViewData\ViewData;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EditViewData extends ViewData
{
    private ShoppingRepository $shoppingRepository;

    public function __construct(SessionInterface $session, ShoppingRepository $shoppingRepository)
    {
        parent::__construct($session);
        $this->shoppingRepository = $shoppingRepository;
        $this->options[ViewParameters::LIMIT] = $session->get(Settings::PAGINATION_COUNT);
    }

    public function addEntity(Shop $shop): self
    {
        $this->options[ViewParameters::ENTITY] = $shop;
        $this->options[ViewParameters::SHOPPING] = array_map(
            static fn(Shopping $shopping): array => ShoppingTransformer::toSimpleArray($shopping),
            $this->shoppingRepository->findBy(['shop' => $shop->getId()], ['date' => 'DESC'])
        );

        return $this;
    }

    public function addForm(FormInterface $form): self
    {
        $this->options[ViewParameters::CREATE_FORM] = $form->createView();

        return $this;
    }
}
