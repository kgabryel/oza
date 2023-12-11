<?php

namespace App\Services\Factory;

use App\Config\Message\ProductsGroupMessages;
use App\Controller\Web\BaseController;
use App\Entity\ProductsGroup;
use App\Model\Form\ProductsGroup as ProductsGroupModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductsGroupFactory extends EntityFactory
{
    private SupplyFactory $supplyFactory;

    public function __construct(
        FlashBagInterface $flashBag,
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        SupplyFactory $supplyFactory
    ) {
        parent::__construct($flashBag, $entityManager, $tokenStorage);
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
        $productsGroup = new ProductsGroup();
        $productsGroup->setUser($this->user);
        $productsGroup->setName($data->getName());
        $productsGroup->setUnit($data->getUnit()->getMain() ?? $data->getUnit());
        $productsGroup->setBaseUnit($data->getUnit());
        $productsGroup->setNote($data->getNote());
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
