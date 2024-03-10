<?php

namespace App\Form;

use App\Config\Form\SupplyGroupConfig;
use App\Config\Message\Error\SupplyGroupErrors;
use App\Model\Form\SupplyGroup;
use App\Repository\SupplyGroupRepository;
use App\Services\UserService;
use App\Validator\UniqueForUser\UniqueForUser;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class SupplyGroupForm extends UserForm
{
    private SupplyGroupRepository $repository;

    public function __construct(SupplyGroupRepository $repository, UserService $userService)
    {
        parent::__construct($userService);
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => SupplyGroupConfig::NAME_LABEL,
            'attr' => [
                'maxlength' => SupplyGroupConfig::NAME_MAX_LENGTH
            ],
            'constraints' => [
                new NotBlank([
                    'message' => SupplyGroupErrors::NAME_MISSING
                ]),
                new Type([
                    'type' => 'string',
                    'message' => SupplyGroupErrors::INVALID_VALUE
                ]),
                new Length([
                    'max' => SupplyGroupConfig::NAME_MAX_LENGTH,
                    'maxMessage' => SupplyGroupErrors::NAME_TOO_LONG
                ]),
                new UniqueForUser(
                    $this->user,
                    SupplyGroupErrors::NAME_IN_USE,
                    $this->repository,
                    expect: $options['expect']
                )
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SupplyGroup::class,
            'expect' => 0
        ]);
    }
}
