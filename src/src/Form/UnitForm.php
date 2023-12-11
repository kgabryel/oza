<?php

namespace App\Form;

use App\Config\Form\UnitConfig;
use App\Config\Message\Error\UnitErrors;
use App\Entity\Unit as UnitEntity;
use App\Field\MaterialSwitch;
use App\Model\Form\Unit;
use App\Repository\UnitRepository;
use App\Validator\UniqueForUser\UniqueForUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;

class UnitForm extends UserForm
{
    private const ALL_UNIT_GROUPS = ['main', 'sub'];
    private const SUB_UNIT_GROUPS = ['sub'];
    private UnitRepository $unitRepository;
    private array $units;

    public function __construct(UnitRepository $unitRepository, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($tokenStorage);
        $this->unitRepository = $unitRepository;
        $this->units = $this->unitRepository->findBy([
            'user' => $this->user,
            'main' => null
        ]);
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
                    'message' => UnitErrors::NAME_MISSING,
                    'groups' => self::ALL_UNIT_GROUPS
                ]),
                new Type([
                    'type' => 'string',
                    'message' => UnitErrors::INVALID_VALUE,
                    'groups' => self::ALL_UNIT_GROUPS
                ]),
                new Length([
                    'max' => UnitConfig::NAME_MAX_LENGTH,
                    'maxMessage' => UnitErrors::NAME_TOO_LONG,
                    'groups' => self::ALL_UNIT_GROUPS
                ]),
                new UniqueForUser([
                    UniqueForUser::USER_OPTION => $this->user,
                    UniqueForUser::REPOSITORY_OPTION => $this->unitRepository,
                    UniqueForUser::COLUMN_NAME_OPTION => 'name',
                    UniqueForUser::MESSAGE_OPTION => UnitErrors::NAME_IN_USE,
                    'groups' => self::ALL_UNIT_GROUPS
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
                        'message' => UnitErrors::SHORTCUT_MISSING,
                        'groups' => self::ALL_UNIT_GROUPS
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => UnitErrors::INVALID_VALUE,
                        'groups' => self::ALL_UNIT_GROUPS
                    ]),
                    new Length([
                        'max' => UnitConfig::SHORTCUT_MAX_LENGTH,
                        'maxMessage' => UnitErrors::SHORTCUT_TOO_LONG,
                        'groups' => self::ALL_UNIT_GROUPS
                    ]),
                    new UniqueForUser([
                        UniqueForUser::USER_OPTION => $this->user,
                        UniqueForUser::REPOSITORY_OPTION => $this->unitRepository,
                        UniqueForUser::COLUMN_NAME_OPTION => 'shortcut',
                        UniqueForUser::MESSAGE_OPTION => UnitErrors::SHORTCUT_IN_USE,
                        'groups' => self::ALL_UNIT_GROUPS
                    ])
                ]
            ])
            ->add('isMainUnit', MaterialSwitch::class, [
                'label' => UnitConfig::MAIN_UNIT_LABEL
            ])
            ->add('converter', NumberType::class, [
                'label' => UnitConfig::CONVERTER_LABEL,
                'help' => UnitConfig::CONVERTER_HINT,
                'constraints' => [
                    new NotBlank([
                        'message' => UnitErrors::CONVERTER_MISSING,
                        'groups' => self::SUB_UNIT_GROUPS
                    ]),
                    new Type([
                        'type' => 'float',
                        'message' => UnitErrors::INVALID_VALUE,
                        'groups' => self::SUB_UNIT_GROUPS
                    ]),
                    new Positive([
                        'message' => UnitErrors::INVALID_CONVERTER,
                        'groups' => self::SUB_UNIT_GROUPS
                    ])
                ]
            ])
            ->add('mainUnit', EntityType::class, [
                'label' => UnitConfig::MAIN_UNIT_LABEL,
                'choices' => $this->units,
                'class' => UnitEntity::class,
                'invalid_message' => UnitErrors::INVALID_UNIT,
                'constraints' => [
                    new NotBlank([
                        'message' => UnitErrors::UNIT_MISSING,
                        'groups' => self::SUB_UNIT_GROUPS
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Unit::class,
            'validation_groups' => function (FormInterface $form) {
                /** @var Unit $data */
                $data = $form->getData();
                if (!$data->getIsMainUnit()) {
                    return self::ALL_UNIT_GROUPS;
                }

                return ['main'];
            }
        ]);
    }
}
