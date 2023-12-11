<?php

namespace App\Form;

use App\Config\Form\ApiKeyConfig;
use App\Config\Message\Error\ApiKeyErrors;
use App\Field\Wysiwyg;
use App\Model\Form\ApiKeyDescription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Type;

class EditApiKeyForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('description', Wysiwyg::class, [
            'label' => ApiKeyConfig::DESCRIPTION_LABEL,
            'constraints' => [
                new Type([
                    'type' => 'string',
                    'message' => ApiKeyErrors::INVALID_VALUE
                ])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApiKeyDescription::class
        ]);
    }
}
