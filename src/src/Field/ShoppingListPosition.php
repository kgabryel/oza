<?php

namespace App\Field;

use App\Config\Form\ShoppingListConfig;
use App\Config\Message\Error\ShoppingListErrors;
use App\Entity\Shop;
use App\Entity\Unit;
use App\Repository\ShopRepository;
use App\Repository\UnitRepository;
use App\Services\PositionFactory\PositionFactory;
use App\Transformer\ShoppingListPositionTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class ShoppingListPosition extends AbstractType
{
    private PositionFactory $factory;
    private array $shops;
    private array $units;

    public function __construct(
        PositionFactory $factory,
        TokenStorageInterface $tokenStorage,
        UnitRepository $unitRepository,
        ShopRepository $shopRepository
    ) {
        $this->factory = $factory;
        $user = $tokenStorage->getToken()->getUser();
        $this->shops = $shopRepository->findForUser($user);
        $this->units = $unitRepository->findForUser($user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new ShoppingListPositionTransformer($this->factory));
        $builder->add('position', HiddenType::class)
            ->add('type', HiddenType::class)
            ->add('unit', EntityType::class, [
                'choices' => $this->units,
                'class' => Unit::class,
                'invalid_message' => ShoppingListErrors::INVALID_UNIT,
                'error_bubbling' => false
            ])
            ->add('shop', EntityType::class, [
                'choices' => $this->shops,
                'class' => Shop::class,
                'invalid_message' => ShoppingListErrors::INVALID_SHOP,
                'error_bubbling' => false
            ])
            ->add('amount', HiddenType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => ShoppingListErrors::AMOUNT_MISSING
                    ]),
                    new Positive([
                        'message' => ShoppingListErrors::AMOUNT_TOO_SMALL
                    ])
                ],
                'error_bubbling' => false
            ])
            ->add('checked', MaterialSwitch::class)
            ->add('description', Wysiwyg::class, [
                'label' => ShoppingListConfig::NOTE_LABEL,
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => ShoppingListErrors::INVALID_VALUE
                    ])
                ],
                'error_bubbling' => false
            ]);
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'multiple' => true
        ]);
    }
}
