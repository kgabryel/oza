<?php

namespace App\Controller\Web;

use App\Config\Message\ShoppingMessages;
use App\Form\ShoppingForm;
use App\Services\Entity\ShoppingService;
use App\Services\Factory\ShoppingFactory;
use App\ViewData\Shopping\IndexViewData;
use Symfony\Component\HttpFoundation\Response;

final class ShoppingController extends BaseController
{
    public const INDEX_TEMPLATE = 'shopping/index';
    public const INDEX_URL = 'shopping.index';

    public function destroy(int $id, ShoppingService $shoppingService): Response
    {
        if ($shoppingService->find($id)) {
            $shoppingService->remove();
        } else {
            $this->addErrorMessage(ShoppingMessages::DELETE_INCORRECT);
        }

        return $this->redirect($this->generateUrl(self::INDEX_URL));
    }

    public function index(IndexViewData $indexViewData): Response
    {
        $indexViewData->addCreateForm($this->createForm(ShoppingForm::class));

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    public function store(IndexViewData $indexViewData, ShoppingFactory $shoppingFactory): Response
    {
        $form = $this->createForm(ShoppingForm::class);
        if ($shoppingFactory->create($form, $this->request)) {
            return $this->redirectBack();
        }
        $indexViewData->addCreateForm($form);

        return $this->render(self::INDEX_TEMPLATE, $indexViewData->getOptions());
    }

    protected function getActive(): int
    {
        return 4;
    }
}
