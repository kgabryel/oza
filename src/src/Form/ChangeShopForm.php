<?php

namespace App\Form;

use App\Config\Message\Error\ShoppingListErrors;
use App\Entity\Shop;
use App\Model\Form\ChangeShop;
use App\Repository\ShopRepository;
use App\Services\UserService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeShopForm extends UserForm
{
    private array $shops;

    public function __construct(ShopRepository $shopRepository, UserService $userService)
    {
        parent::__construct($userService);
        $this->shops = $shopRepository->findForUser($this->user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('shop', EntityType::class, [
            'choices' => $this->shops,
            'invalid_message' => ShoppingListErrors::INVALID_SHOP,
            'class' => Shop::class
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangeShop::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
