<?php

namespace App\Form\Filters;

use App\Config\Form\SupplyConfig;
use App\Config\Message\Error\SupplyErrors;
use App\Entity\ProductsGroup;
use App\Entity\SupplyGroup;
use App\Entity\Unit;
use App\Form\UserForm;
use App\Model\Filter\Supply;
use App\Repository\ProductsGroupRepository;
use App\Repository\SupplyGroupRepository;
use App\Repository\UnitRepository;
use App\Services\UserService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplyFindForm extends UserForm
{
    private array $groups;
    private array $productsGroups;
    private array $units;

    public function __construct(
        UnitRepository $repository,
        ProductsGroupRepository $productsGroupRepository,
        UserService $userService,
        SupplyGroupRepository $supplyGroupRepository
    ) {
        parent::__construct($userService);
        $this->units = $repository->findBy([
            'user' => $this->user
        ]);
        $this->productsGroups = $productsGroupRepository->findForUser($this->user);
        $this->groups = $supplyGroupRepository->findForUser($this->user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('amountMin', NumberType::class, [
            'label' => SupplyConfig::AMOUNT_MIN_LABEL
        ])
            ->add('amountMax', NumberType::class, [
                'label' => SupplyConfig::AMOUNT_MAX_LABEL
            ])
            ->add('productsGroups', EntityType::class, [
                'label' => SupplyConfig::PRODUCTS_GROUP_LABEL,
                'multiple' => true,
                'choices' => $this->productsGroups,
                'class' => ProductsGroup::class,
                'invalid_message' => SupplyErrors::INVALID_VALUE
            ])
            ->add('units', EntityType::class, [
                'label' => SupplyConfig::UNIT_LABEL,
                'multiple' => true,
                'choices' => $this->units,
                'class' => Unit::class,
                'invalid_message' => SupplyErrors::INVALID_VALUE
            ])
            ->add('groups', EntityType::class, [
                'label' => SupplyConfig::SUPPLY_GROUPS_LABEL,
                'multiple' => true,
                'choices' => $this->groups,
                'class' => SupplyGroup::class,
                'invalid_message' => SupplyErrors::INVALID_VALUE
            ])
            ->setMethod('GET');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Supply::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
