<?php

namespace App\Controller\Web;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/** @method User getUser() */
abstract class BaseController extends AbstractController
{
    public const ERROR_MESSAGE = 'errorMessage';
    public const SUCCESS_MESSAGE = 'successMessage';
    protected Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    protected function addErrorMessage(string $message): void
    {
        $this->addFlash(self::ERROR_MESSAGE, $message);
    }

    protected function addSuccessMessage(string $message): void
    {
        $this->addFlash(self::SUCCESS_MESSAGE, $message);
    }

    protected function redirectBack(): Response
    {
        return $this->redirect($this->request->headers->get('referer'));
    }

    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $parameters['active'] = $this->getActive();
        $menu = [
            [
                'icon' => 'mdi-cogs',
                'name' => 'Ustawienia',
                'href' => $this->generateUrl(SettingsController::INDEX_URL)
            ],
            [
                'icon' => 'mdi-scale-balance',
                'name' => 'Jednostki miar',
                'href' => $this->generateUrl(UnitsController::INDEX_URL)
            ],
            [
                'icon' => 'mdi-home',
                'name' => 'Sklepy',
                'href' => $this->generateUrl(ShopsController::INDEX_URL)
            ],
            [
                'icon' => 'mdi-shoe-print',
                'name' => 'Marki',
                'href' => $this->generateUrl(BrandsController::INDEX_URL)
            ],
            [
                'icon' => 'mdi-basket',
                'name' => 'Zakupy',
                'href' => $this->generateUrl(ShoppingController::INDEX_URL)
            ],
            [
                'icon' => 'mdi-cookie',
                'name' => 'Grupy produktów',
                'href' => $this->generateUrl(ProductsGroupsController::INDEX_URL)
            ],
            [
                'icon' => 'mdi-food-apple',
                'name' => 'Produkty',
                'href' => $this->generateUrl(ProductsController::INDEX_URL)
            ],
            [
                'icon' => 'mdi-clipboard-list',
                'name' => 'Listy zakupów',
                'href' => $this->generateUrl(ShoppingListsController::INDEX_URL)
            ],
            [
                'icon' => 'mdi-format-list-bulleted-square',
                'name' => 'Szybkie listy',
                'href' => $this->generateUrl(QuickListsController::INDEX_URL)
            ],
            [
                'icon' => 'mdi-bell',
                'name' => 'Powiadomienia',
                'href' => $this->generateUrl(AlertsController::INDEX_URL)
            ],
            [
                'icon' => 'mdi-fridge',
                'name' => 'Zapasy',
                'href' => $this->generateUrl(SuppliesController::INDEX_URL)
            ],
            [
                'icon' => 'mdi-tag',
                'name' => 'Grupy zapasów',
                'href' => $this->generateUrl(SupplyGroupsController::INDEX_URL)
            ]
        ];
        $parameters['menu'] = json_encode($menu, JSON_THROW_ON_ERROR);
        $parameters['activePage'] = $this->getActive();

        return parent::render(sprintf('%s.html.twig', $view), $parameters, $response);
    }

    abstract protected function getActive(): int;
}
