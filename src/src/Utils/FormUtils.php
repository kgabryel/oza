<?php

namespace App\Utils;

use App\Entity\Product;
use App\Entity\ProductsGroup;
use App\Entity\Unit;
use App\Entity\User;
use App\Repository\ProductsGroupRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class FormUtils
{
    public static function getJsonContent(Request $request): array
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException(400, 'Invalid json');
        }

        return $data;
    }

    public static function getProductsUnits(ProductsGroupRepository $productRepository, User $user): array
    {
        $result = [];
        /** @var ProductsGroup $product */
        foreach ($productRepository->findForUser($user) as $product) {
            $index = null;
            foreach ($result as $key => $value) {
                if ($value['unit'] === $product->getUnit()->getId()) {
                    $index = $key;
                    break;
                }
            }
            if ($index !== null) {
                $result[$index]['products'][] = $product->getId();
            } else {
                $result[] = [
                    'unit' => $product->getUnit()->getId(),
                    'products' => [$product->getId()]
                ];
            }
        }

        return $result;
    }

    /**
     * @param Product[] $products
     *
     * @return array
     */
    public static function productSelectOptions(array $products): array
    {
        $tmp = [];
        foreach ($products as $product) {
            $tmp[(string)$product] = $product->getId();
        }
        ksort($tmp);
        return $tmp;
    }

    /**
     * @param ProductsGroup[] $productsGroups
     *
     * @return array
     */
    public static function productsGroupSelectOptions(array $productsGroups): array
    {
        $tmp = [];
        foreach ($productsGroups as $productsGroup) {
            $tmp[$productsGroup->getName()] = $productsGroup->getId();
        }
        ksort($tmp);
        return $tmp;
    }

    public static function unitSelectOptions(Unit $unit): array
    {
        $result = [];
        if ($unit->getMain() !== null) {
            $unit = $unit->getMain();
        }
        $result[$unit->getId()] = sprintf('%s (%s)', $unit->getName(), $unit->getShortcut());
        foreach ($unit->getUnits() as $position) {
            $result[$position->getId()] = sprintf('%s (%s)', $position->getName(), $position->getShortcut());
        }

        return $result;
    }
}
