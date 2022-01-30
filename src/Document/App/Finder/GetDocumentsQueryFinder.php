<?php

declare(strict_types=1);

namespace App\Document\App\Finder;

use App\Document\App\Query\GetDocumentsQuery;
use App\Document\Domain\Repository\DocumentRepositoryInterface;

class GetDocumentsQueryFinder
{
    private DocumentRepositoryInterface $repository;

    public function __construct(DocumentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetDocumentsQuery $query)
    {
        return $this->repository->findAll();
    }
}
