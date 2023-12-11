<?php

namespace App\Services\Condition;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseCondition extends Condition
{
    public function __invoke(): Response
    {
        $result = $this->decide() ?? new Response(null, Response::HTTP_NO_CONTENT);

        return $result instanceof Response ? $result : new JsonResponse($result);
    }
}
