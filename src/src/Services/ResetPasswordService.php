<?php

namespace App\Services;

use App\Config\Email;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class ResetPasswordService
{
    private FormInterface $form;
    private MailerInterface $mailer;
    private ResetPasswordHelperInterface $resetPasswordHelper;
    private UserRepository $userRepository;

    public function __construct(
        ResetPasswordHelperInterface $resetPasswordHelper,
        MailerInterface $mailer,
        UserRepository $userRepository
    ) {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function changePassword(
        string $token,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): void {
        /** @var User $user */
        $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        $this->resetPasswordHelper->removeResetRequest($token);
        $encodedPassword = $userPasswordHasher->hashPassword($user, $this->form->getData()->getNewPassword());

        $user->setPassword($encodedPassword);
        $entityManager->flush();
    }

    public function checkForm(Request $request): bool
    {
        $this->form->handleRequest($request);

        return $this->form->isSubmitted() && $this->form->isValid();
    }

    public function sendResetEmail(): void
    {
        $email = $this->form->getData()->getEmail();
        $user = $this->userRepository->findOneBy([
            'email' => $email,
            'userType' => 1
        ]);
        if ($user === null) {
            return;
        }
        $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        $email = (new TemplatedEmail())
            ->from(Address::create(Email::EMAIL_SUBJECT))
            ->to($user->getEmail())
            ->subject('Oza - reset hasła')
            ->htmlTemplate('resetPassword/email.html.twig')
            ->context([
                'resetToken' => $resetToken
            ]);

        $this->mailer->send($email);
    }

    public function setForm(FormInterface $form): self
    {
        $this->form = $form;

        return $this;
    }
}
