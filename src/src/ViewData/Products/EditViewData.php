<?php

namespace App\ViewData\Products;

use App\Config\Settings;
use App\Config\ViewParameters;
use App\Entity\Photo;
use App\Entity\Product;
use App\Entity\Shopping;
use App\Services\Transformer\PhotoTransformer;
use App\Services\Transformer\ShoppingTransformer;
use App\ViewData\ViewData;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class EditViewData extends ViewData
{
    private RouterInterface $router;

    public function __construct(SessionInterface $session, RouterInterface $router)
    {
        parent::__construct($session);
        $this->router = $router;
        $this->options[ViewParameters::LIMIT] = $session->get(Settings::PAGINATION_COUNT);
    }

    public function addEntity(Product $product): self
    {
        $this->options[ViewParameters::ENTITY] = $product;
        $this->options[ViewParameters::SHOPPING] = array_map(
            fn(Shopping $shopping): array => ShoppingTransformer::toFullArray(
                $shopping,
                $this->router
            ),
            $product->getShopping()->toArray()
        );
        $mainPhoto = $product->getMainPhoto()?->getId();
        $photos = array_map(
            static fn(Photo $photo): array => PhotoTransformer::toArray(
                $photo,
                $photo->getId() === $mainPhoto,
                true
            ),
            $product->getPhotos()->toArray()
        );
        foreach ($product->getGroups() as $group) {
            foreach ($group->getPhotos() as $photo) {
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
