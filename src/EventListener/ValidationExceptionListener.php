<?php

namespace App\EventListener;

use App\Exception\ValidatorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\ConstraintViolation;

class ValidationExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if($exception instanceof ValidatorException)
        {

            $message = ['errors' => []];

            /** @var ConstraintViolation $error */
            foreach ($exception->getValidationList() as $error) {
                $message['errors'][] = [
                    'message' => $error->getMessage(),
                    'field' => $error->getPropertyPath(),
                    'details' => $error->getInvalidValue()];
            }

            $event->setResponse(new JsonResponse($message, 400));
        }

    }

}