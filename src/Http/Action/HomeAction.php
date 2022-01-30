<?php

declare(strict_types=1);

namespace App\Http\Action;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeAction
{
    public function __invoke(Request $request)
    {
        return new JsonResponse('TBD : API DOCUMENTATION', Response::HTTP_OK);
    }
}
