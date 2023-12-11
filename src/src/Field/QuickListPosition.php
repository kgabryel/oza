<?php

namespace App\Field;

use App\Config\Form\QuickListConfig;
use App\Config\Message\Error\QuickListErrors;
use App\Model\Form\QuickListPosition as QuickListPositionModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class QuickListPosition extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('position', TextType::class, [
            'constraints' => [
                new Type([
                    'type' => 'string',
                    'message' => QuickListErrors::INVALID_VALUE
                ]),
                new Length([
                    'max' => QuickListConfig::POSITION_MAX_LENGTH,
                    'maxMessage' => QuickListErrors::INVALID_POSITION
                ])
            ]
        ])
            ->add('checked', MaterialSwitch::class);
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'multiple' => true,
            'data_class' => QuickListPositionModel::class
        ]);
    }
}
