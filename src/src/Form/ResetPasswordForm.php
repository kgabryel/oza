<?php

namespace App\Form;

use App\Config\Form\RegisterConfig;
use App\Config\Form\ResetPasswordConfig;
use App\Config\Message\Error\RegisterErrors;
use App\Model\Form\NewPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ResetPasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newPassword', RepeatedType::class, [
                'empty_data' => '',
                'constraints' => [
                    new NotBlank([
                        'message' => RegisterErrors::EMPTY_PASSWORD
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => RegisterErrors::INVALID_VALUE
                    ]),
                    new Length([
                        'max' => RegisterConfig::PASSWORD_MAX_LENGTH,
                        'maxMessage' => RegisterErrors::PASSWORD_TOO_LONG
                    ])
                ],
                'type' => PasswordType::class,
                'invalid_message' => RegisterErrors::DIFFERENT_PASSWORDS,
                'first_options' => [
                    'label' => ResetPasswordConfig::NEW_PASSWORD_LABEL,
                    'attr' => [
                        'maxlength' => RegisterConfig::PASSWORD_MAX_LENGTH
                    ]
                ],
                'second_options' => [
                    'label' => ResetPasswordConfig::NEW_PASSWORD_REPEAT_LABEL,
                    'attr' => [
                        'maxlength' => RegisterConfig::PASSWORD_MAX_LENGTH
                    ]
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NewPassword::class
        ]);
    }
}
