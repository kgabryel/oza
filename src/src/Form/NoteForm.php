<?php

namespace App\Form;

use App\Config\Form\NoteConfig;
use App\Config\Message\Error\NoteErrors;
use App\Field\Wysiwyg;
use App\Model\Form\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class NoteForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('content', Wysiwyg::class, [
            'label' => NoteConfig::CONTENT_LABEL,
            'constraints' => [
                new NotBlank([
                    'message' => NoteErrors::CONTENT_MISSING
                ]),
                new Type([
                    'type' => 'string',
                    'message' => NoteErrors::INVALID_VALUE
                ])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class
        ]);
    }
}
