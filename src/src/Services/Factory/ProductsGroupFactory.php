<?php

namespace App\Services\Factory;

use App\Config\Message\ProductsGroupMessages;
use App\Controller\Web\BaseController;
use App\Entity\ProductsGroup;
use App\Entity\Unit;
use App\Model\Form\ProductsGroup as ProductsGroupModel;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ProductsGroupFactory extends EntityFactory
{
    private SupplyFactory $supplyFactory;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        UserService $userService,
        SupplyFactory $supplyFactory
    ) {
        parent::__construct($flashBag, $entityManager, $userService);
        $this->supplyFactory = $supplyFactory;
    }

    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var ProductsGroupModel $data */
        $data = $form->getData();
        /** @var Unit $unit */
        $unit = $data->getUnit();
        $productsGroup = new ProductsGroup();
        $productsGroup->setUser($this->user)
            ->setName($data->getName())
            ->setUnit($unit->getMain() ?? $unit)
            ->setBaseUnit($unit)
            ->setNote($data->getNote());
        if ($data->getCreateSupply()) {
            $this->entityManager->persist($productsGroup);
            $this->supplyFactory->createForProductsGroup($productsGroup);
        } else {
            $this->saveEntity($productsGroup);
        }
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ProductsGroupMessages::CREATED_CORRECTLY);

        return true;
    }
}
