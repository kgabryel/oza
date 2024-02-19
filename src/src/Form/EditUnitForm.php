<?php

namespace App\Form;

use App\Config\Form\UnitConfig;
use App\Config\Message\Error\UnitErrors;
use App\Model\Form\EditUnit;
use App\Repository\UnitRepository;
use App\Validator\UniqueForUser\UniqueForUser;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class EditUnitForm extends UserForm
{
    private UnitRepository $repository;

    public function __construct(UnitRepository $repository, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($tokenStorage);
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => UnitConfig::NAME_LABEL,
            'attr' => [
                'maxlength' => UnitConfig::NAME_MAX_LENGTH
            ],
            'constraints' => [
                new NotBlank([
                    'message' => UnitErrors::NAME_MISSING
                ]),
                new Type([
                    'type' => 'string',
                    'message' => UnitErrors::INVALID_VALUE
                ]),
                new Length([
                    'max' => UnitConfig::NAME_MAX_LENGTH,
                    'maxMessage' => UnitErrors::NAME_TOO_LONG
                ]),
                new UniqueForUser([
                    UniqueForUser::USER_OPTION => $this->user,
                    UniqueForUser::REPOSITORY_OPTION => $this->repository,
                    UniqueForUser::COLUMN_NAME_OPTION => 'name',
                    UniqueForUser::EXPECT_OPTION => $options['expect'],
                    UniqueForUser::MESSAGE_OPTION => UnitErrors::NAME_IN_USE
                ])
            ]
        ])
            ->add('shortcut', TextType::class, [
                'label' => UnitConfig::SHORTCUT_LABEL,
                'attr' => [
                    'maxlength' => UnitConfig::SHORTCUT_MAX_LENGTH
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => UnitErrors::SHORTCUT_MISSING
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => UnitErrors::INVALID_VALUE
                    ]),
                    new Length([
                        'max' => UnitConfig::SHORTCUT_MAX_LENGTH,
                        'maxMessage' => UnitErrors::SHORTCUT_TOO_LONG
                    ]),
                    new UniqueForUser([
                        UniqueForUser::USER_OPTION => $this->user,
                        UniqueForUser::REPOSITORY_OPTION => $this->repository,
                        UniqueForUser::COLUMN_NAME_OPTION => 'shortcut',
                        UniqueForUser::EXPECT_OPTION => $options['expect'],
                        UniqueForUser::MESSAGE_OPTION => UnitErrors::SHORTCUT_IN_USE
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EditUnit::class,
            'expect' => 0
        ]);
    }
}
