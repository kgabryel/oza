<?php

namespace App\Field;

use App\Config\Message\Error\ShoppingErrors;
use App\Entity\Supply;
use App\Entity\Unit;
use App\Repository\SupplyRepository;
use App\Repository\UnitRepository;
use App\Services\PositionFactory\PositionFactory;
use App\Services\UserService;
use App\Transformer\ShoppingTransformer;
use App\Validator\GreaterThanOrEqual;
use App\Validator\ValidShoppingSupply;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Context\ExecutionContext;

class ShoppingPosition extends AbstractType
{
    private PositionFactory $factory;
    private array $supplies;
    private array $units;

    public function __construct(
        UnitRepository $unitRepository,
        UserService $userService,
        PositionFactory $factory,
        SupplyRepository $supplyRepository
    ) {
        $this->factory = $factory;
        $user = $userService->getUser();
        $this->units = $unitRepository->findForUser($user);
        $this->supplies = $supplyRepository->findForUser($user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new ShoppingTransformer($this->factory));
        $builder->add('position', HiddenType::class)
            ->add('type', HiddenType::class)
            ->add('price', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => ShoppingErrors::PRICE_MISSING
                    ]),
                    new Positive([
                        'message' => ShoppingErrors::PRICE_TOO_SMALL
                    ])
                ],
                'error_bubbling' => false
            ])
            ->add('discount', NumberType::class, [
                'constraints' => [
                    new Positive([
                        'message' => ShoppingErrors::DISCOUNT_TOO_SMALL
                    ]),
                    new Callback(function($value, ExecutionContext $context) {
                        $validator = new GreaterThanOrEqual(
                            $context,
                            'price',
                            ShoppingErrors::DISCOUNT_INVALID
                        );
                        $validator->validate($value);
                    })
                ],
                'error_bubbling' => false
            ])
            ->add('amount', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => ShoppingErrors::AMOUNT_MISSING
                    ]),
                    new Positive([
                        'message' => ShoppingErrors::AMOUNT_TOO_SMALL
                    ])
                ],
                'error_bubbling' => false
            ])
            ->add('unit', EntityType::class, [
                'choices' => $this->units,
                'class' => Unit::class,
                'invalid_message' => ShoppingErrors::INVALID_UNIT,
                'constraints' => [
                    new NotBlank([
                        'message' => ShoppingErrors::UNIT_MISSING
                    ]),
                ],
                'error_bubbling' => false
            ])
            ->add('createSupply', MaterialSwitch::class)
            ->add('supply', EntityType::class, [
                'choices' => $this->supplies,
                'class' => Supply::class,
                'invalid_message' => ShoppingErrors::INVALID_SUPPLY,
                'constraints' => [
                    new Callback(function($value, ExecutionContext $context) {
                        $validator = new ValidShoppingSupply($context, $this->factory);
                        $validator->validate($value);
                    })
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
