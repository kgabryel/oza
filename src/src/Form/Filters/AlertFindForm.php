<?php

namespace App\Form\Filters;

use App\Config\Form\AlertConfig;
use App\Config\Message\Error\AlertErrors;
use App\Entity\AlertType;
use App\Field\MultiSelect;
use App\Model\Filter\Alert;
use App\Repository\AlertTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlertFindForm extends AbstractType
{
    private array $types;

    public function __construct(AlertTypeRepository $repository)
    {
        $this->types = $repository->findAll();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('description', TextType::class, [
            'label' => AlertConfig::DESCRIPTION_LABEL
        ])
            ->add('types', EntityType::class, [
                'label' => AlertConfig::TYPE_LABEL,
                'multiple' => true,
                'choices' => $this->types,
                'class' => AlertType::class,
                'invalid_message' => AlertErrors::INVALID_VALUE
            ])
            ->add('statuses', MultiSelect::class, [
                'label' => AlertConfig::STATUS_LABEL,
                'multiple' => true,
                'invalid_message' => AlertErrors::INVALID_VALUE,
                'choices' => [
                    'Aktywne' => 1,
                    'Nieaktywne' => 2
                ]
            ])
            ->setMethod('GET');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alert::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
