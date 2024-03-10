<?php

namespace App\Services\Condition;

use Closure;

abstract class Condition
{
    private Closure $condition;
    private Closure $failAction;
    private Closure $successAction;

    public function __construct(Closure $condition)
    {
        $this->condition = $condition;
        $this->successAction = static fn() => null;
        $this->failAction = static fn() => null;
    }

    abstract public function __invoke(): mixed;

    public function setFailAction(Closure $failAction): void
    {
        $this->failAction = $failAction;
    }

    public function setSuccessAction(Closure $successAction): void
    {
        $this->successAction = $successAction;
    }

    protected function decide(): mixed
    {
        return ($this->condition)() ? ($this->successAction)() : ($this->failAction)();
    }
}
