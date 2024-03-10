<?php

namespace App\Form;

use App\Config\Form\ProductsGroupConfig;
use App\Config\Message\Error\ProductsGroupErrors;
use App\Entity\Unit;
use App\Field\MaterialSwitch;
use App\Field\Wysiwyg;
use App\Model\Form\ProductsGroup;
use App\Repository\ProductsGroupRepository;
use App\Repository\UnitRepository;
use App\Services\UserService;
use App\Validator\UniqueForUser\UniqueForUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ProductsGroupForm extends UserForm
{
    private ProductsGroupRepository $productRepository;
    private array $units;

    public function __construct(
        ProductsGroupRepository $productRepository,
        UnitRepository $unitRepository,
        UserService $userService
    ) {
        parent::__construct($userService);
        $this->productRepository = $productRepository;
        $this->units = $unitRepository->findForUser($this->user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => ProductsGroupConfig::NAME_LABEL,
            'attr' => [
                'maxlength' => ProductsGroupConfig::NAME_MAX_LENGTH
            ],
            'constraints' => [
                new NotBlank([
                    'message' => ProductsGroupErrors::NAME_MISSING
                ]),
                new Type([
                    'type' => 'string',
                    'message' => ProductsGroupErrors::INVALID_VALUE
                ]),
                new Length([
                    'max' => ProductsGroupConfig::NAME_MAX_LENGTH,
                    'maxMessage' => ProductsGroupErrors::NAME_TOO_LONG
                ]),
                new UniqueForUser(
                    $this->user,
                    ProductsGroupErrors::NAME_IN_USE,
                    $this->productRepository
                )
            ]
        ])
            ->add('note', Wysiwyg::class, [
                'label' => ProductsGroupConfig::DESCRIPTION_LABEL,
                'constraints' => [
                    new Type([
                        'type' => 'string',
                        'message' => ProductsGroupErrors::INVALID_VALUE
                    ])
                ]
            ])
            ->add('unit', EntityType::class, [
                'label' => ProductsGroupConfig::BASE_UNIT_LABEL,
                'choices' => $this->units,
                'class' => Unit::class,
                'invalid_message' => ProductsGroupErrors::INVALID_UNIT,
                'constraints' => [
                    new NotBlank([
                        'message' => ProductsGroupErrors::UNIT_MISSING
                    ])
                ]
            ])
            ->add('createSupply', MaterialSwitch::class, [
                'label' => ProductsGroupConfig::SUPPLY_LABEL
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductsGroup::class
        ]);
    }
}
