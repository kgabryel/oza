<?php

namespace App\ViewData\ProductsGroups;

use App\Config\Settings;
use App\Config\ViewParameters;
use App\Entity\Photo;
use App\Entity\ProductsGroup;
use App\Entity\Shopping as ShoppingEntity;
use App\Entity\User;
use App\Model\Filter\Shopping;
use App\Repository\ShoppingRepository;
use App\Services\Transformer\PhotoTransformer;
use App\Services\Transformer\ShoppingTransformer;
use App\Services\UserService;
use App\ViewData\ViewData;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class EditViewData extends ViewData
{
    private RouterInterface $router;
    private ShoppingRepository $shoppingRepository;
    private User $user;

    public function __construct(
        SessionInterface $session,
        ShoppingRepository $shoppingRepository,
        UserService $userService,
        RouterInterface $router
    ) {
        parent::__construct($session);
        $this->shoppingRepository = $shoppingRepository;
        $this->user = $userService->getUser();
        $this->router = $router;
        $this->options[ViewParameters::LIMIT] = $session->get(Settings::PAGINATION_COUNT);
    }

    public function addEntity(ProductsGroup $productsGroup): self
    {
        $shopping = new Shopping();
        $shopping->setProductsGroups(new ArrayCollection([$productsGroup]));
        $this->options[ViewParameters::ENTITY] = $productsGroup;
        $this->options[ViewParameters::SHOPPING] = array_map(
            fn(ShoppingEntity $shopping): array => ShoppingTransformer::toFullArray(
                $shopping,
                $this->router
            ),
            $this->shoppingRepository->filter($this->user, $shopping)
        );
        $mainPhoto = $productsGroup->getMainPhoto()?->getId();
        $photos = array_map(
            static fn(Photo $photo): array => PhotoTransformer::toArray($photo, $photo->getId() === $mainPhoto, true),
            $productsGroup->getPhotos()->toArray()
        );
        foreach ($productsGroup->getProducts() as $product) {
            foreach ($product->getPhotos() as $photo) {
                $photos[] = PhotoTransformer::toArray($photo, $photo->getId() === $mainPhoto);
            }
        }
        $this->options[ViewParameters::PHOTOS] = $photos;

        return $this;
    }

    public function addForm(FormInterface $form): self
    {
        $this->options[ViewParameters::FORM] = $form->createView();

        return $this;
    }
}
