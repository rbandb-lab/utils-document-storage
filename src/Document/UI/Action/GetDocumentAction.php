<?php

declare(strict_types=1);

namespace App\Document\UI\Action;

use App\Document\App\Query\GetDocumentQuery;
use App\Http\Infra\Request\EnveloppeTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GetDocumentAction
{
    use EnveloppeTrait;

    private MessageBusInterface $queryBus;
    private NormalizerInterface $normalizer;

    public function __construct(
        MessageBusInterface $queryBus,
        NormalizerInterface $normalizer
    ) {
        $this->queryBus = $queryBus;
        $this->normalizer = $normalizer;
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $query = $this->queryBus->dispatch(new GetDocumentQuery($id));
        $document = $this->handle($query);

        return new JsonResponse(
            $this->normalizer->normalize($document, 'json'),
            Response::HTTP_OK
        );
    }
}
