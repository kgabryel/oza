<?php

namespace App\Services\Factory;

use App\Config\Message\SupplyMessages;
use App\Controller\Web\BaseController;
use App\Entity\Supply;
use App\Entity\SupplyPart;
use App\Model\Form\ShoppingPosition;
use App\Model\Form\SupplyPart as SupplyPartModel;
use DateTime;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SupplyPartFactory extends EntityFactory
{
    public function create(FormInterface $form, Request $request, Supply $supply): bool
    {
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return false;
        }
        /** @var SupplyPartModel $data */
        $data = $form->getData();
        $supplyPart = new SupplyPart();
        $supplyPart->setSupply($supply)
            ->setAmount($data->getAmount())
            ->setPart($data->getPart())
            ->setDescription($data->getDescription() ?? '')
            ->setUnit($data->getUnit())
            ->setProduct($data->getProduct());
        $date = $data->getDateOfConsumption();
        $data->isOpen() ? $supplyPart->open() : $supplyPart->close();
        if ($date !== null) {
            $supplyPart->setDateOfConsumption(DateTime::createFromFormat('Y-m-d', $date));
        }
        $this->saveEntity($supplyPart);
        $supply->setUpdatedAt(new DateTime());
        $this->saveEntity($supply);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SupplyMessages::CREATED_CORRECTLY);

        return true;
    }

    public function createFromShoppingPosition(ShoppingPosition $shoppingPosition): void
    {
        /** @var Supply $supply */
        $supply = $shoppingPosition->getSupply();
        $supplyPart = new SupplyPart();
        $supplyPart->setSupply($supply);
        $supplyPart->setAmount($shoppingPosition->getAmount());
        $supplyPart->setPart(1);
        $supplyPart->setUnit($shoppingPosition->getUnit());
        $supplyPart->setDescription('');
        $supplyPart->close();
        $product = $shoppingPosition->getProduct();
        if ($product !== null) {
            $supplyPart->setProduct($product);
        }
        $this->saveEntity($supplyPart);
        $supply->setUpdatedAt(new DateTime());
        $this->saveEntity($supply);
        $this->flashBag->add(BaseController::SUCCESS_MESSAGE, SupplyMessages::CREATED_CORRECTLY);
    }
}
