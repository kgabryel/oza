<?php

namespace App\Field;

use App\Transformer\WysiwygDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class Wysiwyg extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new WysiwygDataTransformer());
        parent::buildForm($builder, $options);
    }

    public function getParent(): string
    {
        return TextareaType::class;
    }
}
