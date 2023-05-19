<?php

namespace App\DTO;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ProjectDTO
{
    #[NotBlank]
    #[Type('string')]
    private $title;

    public static function fromRequest(Request $request): self
    {
        $project = new self();
        $requestData = $request->toArray();
        $project->title = $requestData['title'] ?? '';
        return $project;
    }
}