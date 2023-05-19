<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    public function __construct(protected EntityManagerInterface $entityManager, private ValidatorInterface $validator)
    {
    }

    private $errors;

    protected function isValid($obj)
    {
        $this->errors = $this->validator->validate($obj);
        return $this->errors->count() == 0;
    }

    protected function invalidResponse(): Response
    {
        $message = ['errors' => []];

        /** @var ConstraintViolation $error */
        foreach ($this->errors as $error) {
            $message['errors'][] = [
                'message' => $error->getMessage(),
                'field' => $error->getPropertyPath(),
                'details' => $error->getInvalidValue()];
        }
        return new JsonResponse($message, 400);
    }
}