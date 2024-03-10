<?php

namespace App\Validator\UniqueForUser;

use App\Entity\User;
use App\Repository\FilterForUser;
use Symfony\Component\Validator\Constraint;

class UniqueForUser extends Constraint
{
    private string $columnName;
    private int $expect;
    private string $message;
    private FilterForUser $repository;
    private User $user;

    public function __construct(
        User $user,
        string $message,
        FilterForUser $repository,
        string $columnName = 'name',
        int $expect = 0,
        array $options = []
    ) {
        $this->expect = $expect;
        $this->user = $user;
        $this->message = $message;
        $this->repository = $repository;
        $this->columnName = $columnName;

        parent::__construct($options);
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
