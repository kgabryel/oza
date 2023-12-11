<?php

namespace App\Form;

use App\Config\Form\ShoppingConfig;
use App\Config\Message\Error\ShoppingErrors;
use App\Entity\Shop;
use App\Field\MaterialDate;
use App\Field\ShoppingPosition;
use App\Model\Form\Shopping;
use App\Repository\ShopRepository;
use App\Validator\CorrectUnit\CorrectUnit;
use App\Validator\PositionExists\PositionExists;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShoppingForm extends UserForm
{
    private array $shops;

    public function __construct(ShopRepository $repository, TokenStorageInterface $tokenStorage)
    {
        parent::__construct($tokenStorage);
        $this->shops = $repository->findForUser($this->user);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('shop', EntityType::class, [
            'label' => ShoppingConfig::SHOP_LABEL,
            'choices' => $this->shops,
            'class' => Shop::class,
            'invalid_message' => ShoppingErrors::INVALID_SHOP,
            'constraints' => [
                new NotBlank([
                    'message' => ShoppingErrors::SHOP_MISSING
                ])
            ]
        ])
            ->add('date', MaterialDate::class, [
                'label' => ShoppingConfig::DATE_LABEL,
                'data' => (new DateTime())->format('Y-m-d')
            ])
            ->add(
                'positions',
                CollectionType::class,
                [
                    'entry_type' => ShoppingPosition::class,
                    'allow_add' => true,
                    'entry_options' => [
                        'constraints' => [
                            new PositionExists(),
                            new CorrectUnit()
                        ]
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shopping::class
        ]);
    }
}
