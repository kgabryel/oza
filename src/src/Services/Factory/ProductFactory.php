<?php

namespace App\Services\Factory;

use App\Config\Message\ProductMessages;
use App\Controller\Web\BaseController;
use App\Entity\Product;
use App\Model\Form\Product as ProductModel;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductFactory extends EntityFactory
{
    public function create(FormInterface $form, Request $request): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var ProductModel $data */
        $data = $form->getData();
        $product = new Product();
        $product->setUser($this->user)
            ->setName($data->getName())
            ->setNote($data->getNote())
            ->setUnit($data->getUnit())
            ->setDefaultAmount($data->getDefaultAmount())
            ->setBrand($data->getBrand())
            ->setBarcode($data->getBarcode());
        foreach ($data->getProductsGroups() as $productsGroup) {
            $product->addGroup($productsGroup);
        }
        $this->saveEntity($product);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, ProductMessages::CREATED_CORRECTLY);

        return true;
    }
}
