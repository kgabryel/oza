<?php

namespace App\Form;

use App\Config\Form\RegisterConfig;
use App\Config\Message\Error\RegisterErrors;
use App\Model\Form\UserModel;
use App\Repository\UserRepository;
use App\Validator\UniqueEmail\UniqueEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class RegisterForm extends AbstractType
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', EmailType::class, [
            'label' => RegisterConfig::EMAIL_LABEL,
            'attr' => [
                'maxlength' => RegisterConfig::EMAIL_MAX_LENGTH
            ],
            'constraints' => [
                new NotBlank([
                    'message' => RegisterErrors::EMPTY_EMAIL
                ]),
                new Email([
                    'message' => RegisterErrors::INVALID_EMAIL_FORMAT
                ]),
                new Length([
                    'max' => RegisterConfig::EMAIL_MAX_LENGTH,
                    'maxMessage' => RegisterErrors::EMAIL_TOO_LONG
                ]),
                new UniqueEmail([
                    UniqueEmail::REPOSITORY_OPTION => $this->repository
                ])
            ]
        ])
            ->add('password', RepeatedType::class, [
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
                ],
                'type' => PasswordType::class,
                'invalid_message' => RegisterErrors::DIFFERENT_PASSWORDS,
                'first_options' => [
                    'attr' => [
                        'maxlength' => RegisterConfig::PASSWORD_MAX_LENGTH
                    ],
                    'label' => RegisterConfig::PASSWORD_LABEL
                ],
                'second_options' => [
                    'attr' => [
                        'maxlength' => RegisterConfig::PASSWORD_MAX_LENGTH
                    ],
                    'label' => RegisterConfig::PASSWORD_REPEAT_LABEL
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserModel::class
        ]);
    }
}
