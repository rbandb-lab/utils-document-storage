<?php

declare(strict_types=1);

namespace App\Http\Infra\Request;

use App\Http\Infra\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;

trait JsonDecodeTrait
{
    public function decode(Request $request)
    {
        try {
            $data = json_decode($request->getContent(), true);
        } catch (\Exception $exception) {
            throw BadRequestException::createFromException($exception);
        }

        if (null === $data) {
            throw BadRequestException::createFromMessage('Invalid Json');
        }

        return $data;
    }
}
