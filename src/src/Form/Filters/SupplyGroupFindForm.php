<?php

namespace App\Form\Filters;

use App\Config\Form\SupplyGroupConfig;
use App\Config\Message\Error\SupplyGroupErrors;
use App\Entity\ProductsGroup;
use App\Form\UserForm;
use App\Model\Filter\SupplyGroup;
use App\Repository\ProductsGroupRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SupplyGroupFindForm extends UserForm
{
    private array $productsGroups;

    public function __construct(TokenStorageInterface $tokenStorage, ProductsGroupRepository $productsGroupRepository)
    {
        parent::__construct($tokenStorage);
        $this->productsGroups = $productsGroupRepository->findWithSupply($this->user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => SupplyGroupConfig::NAME_LABEL
        ])
            ->add('productsGroups', EntityType::class, [
                'label' => SupplyGroupConfig::SUPPLY_LABEL,
                'multiple' => true,
                'choices' => $this->productsGroups,
                'class' => ProductsGroup::class,
                'invalid_message' => SupplyGroupErrors::INVALID_VALUE
            ])
            ->setMethod('GET');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SupplyGroup::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
