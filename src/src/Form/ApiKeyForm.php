<?php

namespace App\Form;

use App\Config\Form\ApiKeyConfig;
use App\Config\Message\Error\ApiKeyErrors;
use App\Entity\Application;
use App\Model\Form\ApiKey;
use App\Repository\ApiKeyRepository;
use App\Repository\ApplicationRepository;
use App\Validator\UniqueKey;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Context\ExecutionContext;

class ApiKeyForm extends UserForm
{
    private ApiKeyRepository $apiKeyRepository;
    private array $applications;

    public function __construct(
        ApplicationRepository $applicationRepository,
        TokenStorageInterface $tokenStorage,
        ApiKeyRepository $apiKeyRepository
    ) {
        parent::__construct($tokenStorage);
        $this->applications = $applicationRepository->findAll();

        $this->apiKeyRepository = $apiKeyRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('key', TextType::class, [
            'label' => ApiKeyConfig::KEY_LABEL,
            'attr' => [
                'maxlength' => ApiKeyConfig::KEY_MAX_LENGTH
            ],
            'constraints' => [
                new NotBlank([
                    'message' => ApiKeyErrors::KEY_MISSING
                ]),
                new Type([
                    'type' => 'string',
                    'message' => ApiKeyErrors::INVALID_VALUE
                ]),
                new Length([
                    'max' => ApiKeyConfig::KEY_MAX_LENGTH,
                    'maxMessage' => ApiKeyErrors::KEY_TOO_LONG
                ]),
                new Callback(function ($value, ExecutionContext $context) {
                    $validator = new UniqueKey(
                        $context,
                        ApiKeyErrors::KEY_NOT_UNIQUE,
                        $this->apiKeyRepository,
                        $this->user
                    );
                    $validator->validate($value);
                })
            ]
        ])
            ->add('application', EntityType::class, [
                'label' => ApiKeyConfig::APPLICATION_LABEL,
                'choices' => $this->applications,
                'class' => Application::class,
                'invalid_message' => ApiKeyErrors::INVALID_APPLICATION,
                'constraints' => [
                    new NotBlank([
                        'message' => ApiKeyErrors::APPLICATION_MISSING
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ApiKey::class
        ]);
    }
}
