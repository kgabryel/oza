<?php

namespace App\Form\Filters;

use App\Config\Form\ProductsGroupConfig;
use App\Config\Message\Error\ProductsGroupErrors;
use App\Entity\Unit;
use App\Form\UserForm;
use App\Model\Filter\ProductsGroup;
use App\Repository\UnitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductsGroupFindForm extends UserForm
{
    private array $units;

    public function __construct(UnitRepository $repository, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($tokenStorage);
        $this->units = $repository->findBy([
            'user' => $this->user
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => ProductsGroupConfig::NAME_LABEL
        ])
            ->add('units', EntityType::class, [
                'label' => ProductsGroupConfig::UNIT_LABEL,
                'multiple' => true,
                'class' => Unit::class,
                'choices' => $this->units,
                'invalid_message' => ProductsGroupErrors::INVALID_VALUE
            ])
            ->setMethod('GET');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductsGroup::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
