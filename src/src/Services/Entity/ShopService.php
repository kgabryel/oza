<?php

namespace App\Services\Entity;

use App\Config\Message\ShopsMessages;
use App\Controller\Web\BaseController;
use App\Entity\Shop;
use App\Model\Form\Shop as ShopModel;
use App\Repository\ShopRepository;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ShopService extends EntityService
{
    private Shop $shop;
    private ShopRepository $shopRepository;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        ShopRepository $shopRepository
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->shopRepository = $shopRepository;
    }

    public function find(int $id): bool
    {
        $shop = $this->shopRepository->findById($id, $this->user);

        if ($shop === null) {
            return false;
        }
        $this->shop = $shop;

        return true;
    }

    public function getShop(): Shop
    {
        return $this->shop;
    }

    public function remove(): void
    {
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ShopsMessages::DELETE_CORRECT);
        $this->removeEntity($this->shop);
    }

    public function update(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var ShopModel $data */
        $data = $form->getData();
        $this->shop->setName($data->getName())
            ->setDescription($data->getDescription());
        $this->saveEntity($this->shop);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ShopsMessages::UPDATE_CORRECT);

        return true;
    }
}
