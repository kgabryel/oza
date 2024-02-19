<?php

namespace App\Form;

use App\Config\Form\RegisterConfig;
use App\Config\Form\ResetPasswordConfig;
use App\Config\Message\Error\RegisterErrors;
use App\Model\Form\ChangePassword;
use App\Validator\CorrectPassword\CorrectPassword;
use App\Validator\DifferentPassword;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Context\ExecutionContext;

class ChangePasswordForm extends UserForm
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(TokenStorageInterface $tokenStorage, UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct($tokenStorage);
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('oldPassword', PasswordType::class, [
            'label' => ResetPasswordConfig::OLD_PASSWORD_LABEL,
            'constraints' => [
                new NotBlank([
                    'message' => RegisterErrors::EMPTY_PASSWORD
                ]),
                new Type([
                    'type' => 'string',
                    'message' => RegisterErrors::INVALID_VALUE
                ]),
                new CorrectPassword([
                    CorrectPassword::USER_OPTION => $this->user,
                    CorrectPassword::PASSWORD_HASHER_OPTION => $this->userPasswordHasher
                ])
            ]
        ])
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
                    ]),
                    new Callback(function ($value, ExecutionContext $context) {
                        $validator = new DifferentPassword($context);
                        $validator->validate($value);
                    })
                ],
                'type' => PasswordType::class,
                'invalid_message' => RegisterErrors::DIFFERENT_PASSWORDS,
                'first_options' => [
                    'attr' => [
                        'maxlength' => RegisterConfig::PASSWORD_MAX_LENGTH
                    ],
                    'label' => ResetPasswordConfig::NEW_PASSWORD_LABEL
                ],
                'second_options' => [
                    'attr' => [
                        'maxlength' => RegisterConfig::PASSWORD_MAX_LENGTH
                    ],
                    'label' => ResetPasswordConfig::NEW_PASSWORD_REPEAT_LABEL
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangePassword::class
        ]);
    }
}
