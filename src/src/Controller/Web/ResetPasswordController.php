<?php

namespace App\Controller\Web;

use App\Config\Message\Error\ResetPasswordErrors;
use App\Config\Message\ResetPasswordMessages;
use App\Form\ResetPasswordForm;
use App\Form\ResetPasswordRequestForm;
use App\Services\ResetPasswordService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

final class ResetPasswordController extends AbstractController
{
    private const CHECK_EMAIL_TEMPLATE = 'resetPassword/check-email.html.twig';
    private const REQUEST_TEMPLATE = 'resetPassword/request.html.twig';
    private const RESET_TEMPLATE = 'resetPassword/reset.html.twig';
    private ResetPasswordHelperInterface $resetPasswordHelper;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
    }

    public function changePassword(
        string $token,
        ResetPasswordService $resetPasswordService,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        try {
            $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface) {
            $this->addFlash(BaseController::ERROR_MESSAGE, ResetPasswordErrors::INVALID_TOKEN);

            return $this->redirectToRoute(SecurityController::LOGIN_SHOW_URL);
        }
        $form = $this->createForm(ResetPasswordForm::class);
        $resetPasswordService->setForm($form);
        if ($resetPasswordService->checkForm($request)) {
            $resetPasswordService->changePassword($token, $userPasswordHasher, $entityManager);
            $this->addFlash(BaseController::SUCCESS_MESSAGE, ResetPasswordMessages::PASSWORD_CHANGED);

            return $this->redirectToRoute(SecurityController::LOGIN_SHOW_URL);
        }

        return $this->render(self::RESET_TEMPLATE, [
            'resetForm' => $form->createView()
        ]);
    }

    public function sendEmail(ResetPasswordService $resetPasswordService, Request $request): Response
    {
        $form = $this->createForm(ResetPasswordRequestForm::class);
        $resetPasswordService->setForm($form);
        if ($resetPasswordService->checkForm($request)) {
            $resetPasswordService->sendResetEmail();

            return $this->render(self::CHECK_EMAIL_TEMPLATE);
        }

        return $this->render(self::REQUEST_TEMPLATE, [
            'form' => $form->createView()
        ]);
    }

    public function showChangePassword(string $token): Response
    {
        try {
            $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface) {
            $this->addFlash(BaseController::ERROR_MESSAGE, ResetPasswordErrors::INVALID_TOKEN);

            return $this->redirectToRoute(SecurityController::LOGIN_SHOW_URL);
        }
        $form = $this->createForm(ResetPasswordForm::class);

        return $this->render(self::RESET_TEMPLATE, [
            'form' => $form->createView(),
            'token' => $token
        ]);
    }

    public function showSendEmail(): Response
    {
        $form = $this->createForm(ResetPasswordRequestForm::class);

        return $this->render(self::REQUEST_TEMPLATE, [
            'form' => $form->createView()
        ]);
    }
}
