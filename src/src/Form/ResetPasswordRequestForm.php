<?php

namespace App\Form;

use App\Config\Form\RegisterConfig;
use App\Config\Form\ResetPasswordConfig;
use App\Config\Message\Error\RegisterErrors;
use App\Model\Form\ResetPasswordRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ResetPasswordRequestForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => ResetPasswordConfig::EMAIL_LABEL,
                'attr' => [
                    'maxlength' => RegisterConfig::EMAIL_MAX_LENGTH
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => RegisterErrors::EMPTY_EMAIL
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => RegisterErrors::INVALID_VALUE
                    ]),
                    new Email([
                        'message' => RegisterErrors::INVALID_EMAIL_FORMAT
                    ]),
                    new Length([
                        'max' => RegisterConfig::EMAIL_MAX_LENGTH,
                        'maxMessage' => RegisterErrors::EMAIL_TOO_LONG
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ResetPasswordRequest::class
        ]);
    }
}
