<?php

namespace App\Resolver;

use _PHPStan_532094bc1\Nette\Schema\ValidationException;
use App\Exception\ValidatorException;
use App\Request\ProjectRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProjectResolver implements ValueResolverInterface
{
public function __construct(private ValidatorInterface $validator)
{
}

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() == ProjectRequest::class) {
           $projectDTO = ProjectRequest::fromRequest($request);
           $errors = $this->validator->validate($projectDTO);
           if($errors->count() > 0)
           {
               throw (new ValidatorException())->setValidationList($errors);
           }
           return [$projectDTO];
        }
        return [];
    }
}