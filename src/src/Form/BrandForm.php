<?php

namespace App\Form;

use App\Config\Form\BrandConfig;
use App\Config\Message\Error\BrandErrors;
use App\Field\Wysiwyg;
use App\Model\Form\Brand;
use App\Repository\BrandRepository;
use App\Validator\UniqueForUser\UniqueForUser;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class BrandForm extends UserForm
{
    private BrandRepository $repository;

    public function __construct(BrandRepository $repository, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($tokenStorage);
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => BrandConfig::NAME_LABEL,
            'attr' => [
                'maxlength' => BrandConfig::NAME_MAX_LENGTH
            ],
            'constraints' => [
                new NotBlank([
                    'message' => BrandErrors::NAME_MISSING
                ]),
                new Type([
                    'type' => 'string',
                    'message' => BrandErrors::INVALID_VALUE
                ]),
                new Length([
                    'max' => BrandConfig::NAME_MAX_LENGTH,
                    'maxMessage' => BrandErrors::NAME_TOO_LONG
                ]),
                new UniqueForUser([
                    UniqueForUser::USER_OPTION => $this->user,
                    UniqueForUser::REPOSITORY_OPTION => $this->repository,
                    UniqueForUser::COLUMN_NAME_OPTION => 'name',
                    UniqueForUser::EXPECT_OPTION => $options['expect'],
                    UniqueForUser::MESSAGE_OPTION => BrandErrors::BRAND_IN_USE
                ])
            ]
        ])
            ->add('description', Wysiwyg::class, [
                'label' => BrandConfig::DESCRIPTION_LABEL
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Brand::class,
            'expect' => 0
        ]);
    }
}
