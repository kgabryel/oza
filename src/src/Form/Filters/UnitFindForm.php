<?php

namespace App\Form\Filters;

use App\Config\Form\UnitConfig;
use App\Config\Message\Error\UnitErrors;
use App\Entity\Unit as UnitEntity;
use App\Field\MultiSelect;
use App\Form\UserForm;
use App\Model\Filter\Unit;
use App\Repository\UnitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UnitFindForm extends UserForm
{
    private array $units;

    public function __construct(UnitRepository $unitRepository, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($tokenStorage);
        $this->units = $unitRepository->findBy([
            'user' => $this->user,
            'main' => null
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => UnitConfig::NAME_LABEL
        ])
            ->add('shortcut', TextType::class, [
                'label' => UnitConfig::SHORTCUT_LABEL
            ])
            ->add('types', MultiSelect::class, [
                'label' => UnitConfig::TYPE_LABEL,
                'invalid_message' => UnitErrors::INVALID_VALUE,
                'multiple' => true,
                'choices' => [
                    'Główna' => 1,
                    'Podrzędna' => 2
                ]
            ])
            ->add('units', EntityType::class, [
                'label' => UnitConfig::MAIN_UNIT_LABEL,
                'invalid_message' => UnitErrors::INVALID_VALUE,
                'multiple' => true,
                'choices' => $this->units,
                'class' => UnitEntity::class
            ])
            ->setMethod('GET');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Unit::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
