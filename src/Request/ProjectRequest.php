<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProjectRequest
{
    #[NotBlank]
    public $title;

    public static function fromRequest(Request $request): self
    {
        $projectRequest = new self();
        $data = $request->toArray();
        $projectRequest->title = $data['title'] ?? '';
        return $projectRequest;
    }
}