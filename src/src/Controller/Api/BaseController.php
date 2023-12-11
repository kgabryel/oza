<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Services\Condition\Condition;
use App\Services\Condition\ResponseCondition;
use Closure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/** @method User getUser() */
abstract class BaseController extends AbstractController
{
    protected Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        if (!$this->request->isXmlHttpRequest()) {
            throw new BadRequestHttpException('Invalid request.');
        }
    }

    protected function getBaseCondition(Closure $successAction, Closure $condition): Condition
    {
        $conditionObject = new ResponseCondition($condition);
        $conditionObject->setFailAction(fn(): Response => new Response(null, Response::HTTP_FORBIDDEN));
        $conditionObject->setSuccessAction($successAction);

        return $conditionObject;
    }
}
