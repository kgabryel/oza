<?php

namespace App\Form;

use App\Config\Form\SupplyConfig;
use App\Config\Message\Error\SupplyErrors;
use App\Entity\SupplyGroup;
use App\Field\Wysiwyg;
use App\Model\Form\EditSupply;
use App\Repository\SupplyGroupRepository;
use App\Services\UserService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditSupplyForm extends UserForm
{
    private array $supplyGroups;

    public function __construct(UserService $userService, SupplyGroupRepository $supplyGroupRepository)
    {
        parent::__construct($userService);
        $this->supplyGroups = $supplyGroupRepository->findForUser($this->user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', Wysiwyg::class, [
                'label' => SupplyConfig::DESCRIPTION_LABEL
            ])
            ->add('supplyGroups', EntityType::class, [
                'label' => SupplyConfig::SUPPLY_GROUPS_LABEL,
                'invalid_message' => SupplyErrors::INVALID_SUPPLIES_GROUP,
                'multiple' => true,
                'choices' => $this->supplyGroups,
                'class' => SupplyGroup::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditSupply::class
        ]);
    }
}
