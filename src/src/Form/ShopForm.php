<?php

namespace App\Form;

use App\Config\Form\ShopConfig;
use App\Config\Message\Error\ShopErrors;
use App\Field\Wysiwyg;
use App\Model\Form\Shop;
use App\Repository\ShopRepository;
use App\Services\UserService;
use App\Validator\UniqueForUser\UniqueForUser;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ShopForm extends UserForm
{
    private ShopRepository $repository;

    public function __construct(ShopRepository $repository, UserService $userService)
    {
        parent::__construct($userService);
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
            'label' => ShopConfig::NAME_LABEL,
            'attr' => [
                'maxlength' => ShopConfig::NAME_MAX_LENGTH
            ],
            'constraints' => [
                new NotBlank([
                    'message' => ShopErrors::NAME_MISSING
                ]),
                new Type([
                    'type' => 'string',
                    'message' => ShopErrors::INVALID_VALUE
                ]),
                new Length([
                    'max' => ShopConfig::NAME_MAX_LENGTH,
                    'maxMessage' => ShopErrors::NAME_TOO_LONG
                ]),
                new UniqueForUser(
                    $this->user,
                    ShopErrors::SHOP_IN_USE,
                    $this->repository,
                    expect: $options['expect']
                )
            ]
        ])
            ->add('description', Wysiwyg::class, [
                'label' => ShopConfig::DESCRIPTION_LABEL
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shop::class,
            'expect' => 0
        ]);
    }
}
