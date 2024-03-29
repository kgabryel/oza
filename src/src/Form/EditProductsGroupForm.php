<?php

namespace App\Form;

use App\Config\Form\ProductsGroupConfig;
use App\Config\Message\Error\ProductsGroupErrors;
use App\Entity\ProductsGroup;
use App\Entity\Unit;
use App\Field\Wysiwyg;
use App\Model\Form\EditProductsGroup;
use App\Repository\ProductsGroupRepository;
use App\Services\UserService;
use App\Utils\UnitUtils;
use App\Validator\UniqueForUser\UniqueForUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class EditProductsGroupForm extends UserForm
{
    private ProductsGroupRepository $repository;

    public function __construct(ProductsGroupRepository $repository, UserService $userService)
    {
        parent::__construct($userService);
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ProductsGroup $group */
        $group = $this->repository->find($options['expect']);
        $units = UnitUtils::getUnitList($group->getUnit());
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
                    $this->repository,
                    expect: $options['expect']
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
            ->add('baseUnit', EntityType::class, [
                'label' => ProductsGroupConfig::BASE_UNIT_LABEL,
                'choices' => $units,
                'class' => Unit::class,
                'invalid_message' => ProductsGroupErrors::INVALID_BASE_UNIT,
                'constraints' => [
                    new NotBlank([
                        'message' => ProductsGroupErrors::BASE_UNIT_MISSING
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditProductsGroup::class,
            'expect' => 0
        ]);
    }
}
