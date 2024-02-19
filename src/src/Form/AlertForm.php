<?php

namespace App\Form;

use App\Config\Form\AlertConfig;
use App\Config\Message\Error\AlertErrors;
use App\Entity\AlertType;
use App\Field\MaterialSwitch;
use App\Model\Form\Alert;
use App\Repository\AlertTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class AlertForm extends AbstractType
{
    private array $types;

    public function __construct(AlertTypeRepository $repository)
    {
        $this->types = $repository->findBy([], ['id' => 'ASC']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('description', TextareaType::class, [
            'label' => AlertConfig::DESCRIPTION_LABEL,
            'constraints' => [
                new NotBlank([
                    'message' => AlertErrors::INVALID_DESCRIPTION
                ]),
                new Type([
                    'type' => 'string',
                    'message' => AlertErrors::INVALID_VALUE
                ])
            ]
        ])
            ->add('types', EntityType::class, [
                'label' => AlertConfig::TYPES_LABEL,
                'multiple' => true,
                'choices' => $this->types,
                'class' => AlertType::class,
                'invalid_message' => AlertErrors::INVALID_TYPE,
                'constraints' => new Count([
                    'min' => 1,
                    'minMessage' => AlertErrors::TYPE_MISSING
                ])
            ])
            ->add('active', MaterialSwitch::class, [
                'label' => AlertConfig::ACTIVE_LABEL
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alert::class
        ]);
    }
}
