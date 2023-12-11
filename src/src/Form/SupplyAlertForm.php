<?php

namespace App\Form;

use App\Config\Form\SupplyAlertConfig;
use App\Config\Message\Error\SupplyAlertErrors;
use App\Entity\Alert as AlertEntity;
use App\Entity\Unit;
use App\Model\Form\SupplyAlert;
use App\Repository\SupplyAlertRepository;
use App\Repository\SupplyRepository;
use App\Utils\UnitUtils;
use App\Validator\UniqueSupplyAmount;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Context\ExecutionContext;

class SupplyAlertForm extends AbstractType
{
    private SupplyAlertRepository $supplyAlertRepository;
    private SupplyRepository $supplyRepository;

    public function __construct(SupplyAlertRepository $supplyAlertRepository, SupplyRepository $supplyRepository)
    {
        $this->supplyAlertRepository = $supplyAlertRepository;
        $this->supplyRepository = $supplyRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $supply = $this->supplyRepository->find($options['id']);
        $units = UnitUtils::getUnitList($supply->getGroup()->getUnit());
        $builder->add('alert', EntityType::class, [
            'label' => SupplyAlertConfig::ALERT_LABEL,
            'choices' => $options['alerts'],
            'class' => AlertEntity::class,
            'invalid_message' => SupplyAlertErrors::INVALID_ALERT,
            'constraints' => [
                new NotBlank([
                    'message' => SupplyAlertErrors::ALERT_MISSING
                ])
            ]
        ])
            ->add('amount', NumberType::class, [
                'label' => SupplyAlertConfig::AMOUNT_LABEL,
                'constraints' => [
                    new NotBlank([
                        'message' => SupplyAlertErrors::AMOUNT_MISSING
                    ]),
                    new Type([
                        'type' => 'float',
                        'message' => SupplyAlertErrors::INVALID_VALUE
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => SupplyAlertErrors::AMOUNT_TOO_SMALL
                    ]),
                    new Callback(function ($value, ExecutionContext $context) use ($options) {
                        $validator = new UniqueSupplyAmount($context, $options['id'], $this->supplyAlertRepository);
                        $validator->validate($value);
                    })
                ]
            ])
            ->add('unit', EntityType::class, [
                'label' => SupplyAlertConfig::UNIT_LABEL,
                'choices' => $units,
                'class' => Unit::class,
                'invalid_message' => SupplyAlertErrors::INVALID_UNIT,
                'constraints' => [
                    new NotBlank([
                        'message' => SupplyAlertErrors::UNIT_MISSING
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SupplyAlert::class,
            'id' => 0,
            'alerts' => []
        ]);
    }
}
