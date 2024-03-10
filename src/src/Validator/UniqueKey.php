<?php

namespace App\Validator;

use App\Entity\Application;
use App\Entity\User;
use App\Repository\ApiKeyRepository;
use App\Utils\FormUtils;
use Symfony\Component\Validator\Context\ExecutionContext;

class UniqueKey
{
    private ApiKeyRepository $apiKeyRepository;
    private ExecutionContext $context;
    private string $message;
    private User $user;

    public function __construct(
        ExecutionContext $context,
        string $message,
        ApiKeyRepository $apiKeyRepository,
        User $user
    ) {
        $this->context = $context;
        $this->message = $message;
        $this->apiKeyRepository = $apiKeyRepository;
        $this->user = $user;
    }

    public function validate(?string $value): void
    {
        if ($value === null) {
            return;
        }
        $form = FormUtils::getParentForm($this->context);
        $connectedField = $form->get('application');
        if (!$connectedField->isValid()) {
            return;
        }
        /** @var Application $application */
        $application = $connectedField->getNormData();
        $exists = $this->apiKeyRepository->findOneBy([
            'user' => $this->user,
            'key' => $value,
            'application' => $application
        ]);
        if ($exists === null) {
            return;
        }
        $this->context->buildViolation($this->message, [
            '{{ application }}' => $application->getName()
        ])
            ->addViolation();
    }
}
