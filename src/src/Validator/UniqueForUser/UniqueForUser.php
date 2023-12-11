<?php

namespace App\Validator\UniqueForUser;

use App\Entity\User;
use App\Repository\FilterForUser;
use Symfony\Component\Validator\Constraint;

class UniqueForUser extends Constraint
{
    public const COLUMN_NAME_OPTION = 'columnName';
    public const EXPECT_OPTION = 'expect';
    public const MESSAGE_OPTION = 'message';
    public const REPOSITORY_OPTION = 'repository';
    public const USER_OPTION = 'user';
    private string $columnName;
    private int $expect;
    private string $message;
    private FilterForUser $repository;
    private User $user;

    public function __construct(array $options = null)
    {
        $this->expect = $options[self::EXPECT_OPTION] ?? 0;
        $this->user = $options[self::USER_OPTION];
        $this->message = $options[self::MESSAGE_OPTION];
        $this->repository = $options[self::REPOSITORY_OPTION];
        $this->columnName = $options[self::COLUMN_NAME_OPTION];

        parent::__construct(self::clearOptionsArray($options));
    }

    private static function clearOptionsArray(array $options): array
    {
        unset(
            $options[self::EXPECT_OPTION],
            $options[self::USER_OPTION],
            $options[self::COLUMN_NAME_OPTION],
            $options[self::REPOSITORY_OPTION],
            $options[self::MESSAGE_OPTION],
        );

        return $options;
    }

    public function getColumnName(): string
    {
        return $this->columnName;
    }

    public function getExpect(): int
    {
        return $this->expect;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getRepository(): FilterForUser
    {
        return $this->repository;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
