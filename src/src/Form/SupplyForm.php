<?php

namespace App\Form;

use App\Config\Form\SupplyConfig;
use App\Config\Message\Error\SupplyErrors;
use App\Entity\ProductsGroup;
use App\Entity\SupplyGroup;
use App\Field\Wysiwyg;
use App\Model\Form\Supply;
use App\Repository\ProductsGroupRepository;
use App\Repository\SupplyGroupRepository;
use App\Services\UserService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SupplyForm extends UserForm
{
    private array $productsGroups;
    private array $supplyGroups;

    public function __construct(
        UserService $userService,
        ProductsGroupRepository $productsGroupRepository,
        SupplyGroupRepository $supplyGroupRepository
    ) {
        parent::__construct($userService);
        $this->productsGroups = $productsGroupRepository->findWithoutSupply($this->user);
        $this->supplyGroups = $supplyGroupRepository->findForUser($this->user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('productsGroup', EntityType::class, [
            'label' => SupplyConfig::PRODUCTS_GROUP_LABEL,
            'choices' => $this->productsGroups,
            'class' => ProductsGroup::class,
            'invalid_message' => SupplyErrors::INVALID_PRODUCTS_GROUP,
            'constraints' => [
                new NotBlank([
                    'message' => SupplyErrors::PRODUCTS_GROUP_MISSING
                ])
            ]
        ])
            ->add('description', Wysiwyg::class, [
                'label' => SupplyConfig::DESCRIPTION_LABEL
            ])
            ->add('supplyGroups', EntityType::class, [
                'label' => SupplyConfig::SUPPLY_GROUPS_LABEL,
                'multiple' => true,
                'choices' => $this->supplyGroups,
                'invalid_message' => SupplyErrors::INVALID_SUPPLIES_GROUP,
                'class' => SupplyGroup::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Supply::class
        ]);
    }
}
