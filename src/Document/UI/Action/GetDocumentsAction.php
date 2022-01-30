<?php

declare(strict_types=1);

namespace App\Document\UI\Action;

use App\Document\App\Query\GetDocumentsQuery;
use App\Document\Domain\Model\Document;
use App\Http\Infra\Request\EnveloppeTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class GetDocumentsAction
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

    public function __invoke(Request $request)
    {
        $query = $this->queryBus->dispatch(new GetDocumentsQuery());
        $documents = $this->handle($query);
        $normalizedDocuments = count($documents) > 0 ? array_map(function (Document $document) {
            return $this->normalizer->normalize($document, 'json');
        }, $documents) : [];

        return new JsonResponse($normalizedDocuments, Response::HTTP_OK);
    }
}
