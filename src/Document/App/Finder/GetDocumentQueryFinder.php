<?php

declare(strict_types=1);

namespace App\Document\App\Finder;

use App\Document\App\Query\GetDocumentQuery;
use App\Document\Domain\Model\Document;
use App\Document\Domain\Repository\DocumentRepositoryInterface;
use App\Http\Infra\Exception\BadRequestException;

class GetDocumentQueryFinder
{
    private DocumentRepositoryInterface $repository;

    public function __construct(DocumentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetDocumentQuery $query)
    {
        $document = $this->repository->find($query->getId());

        if (!$document instanceof Document) {
            throw BadRequestException::createFromMessage('Document not Found');
        }

        return $document;
    }
}
