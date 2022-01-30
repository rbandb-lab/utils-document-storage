<?php

namespace App\Document\Domain\Repository;

use App\Document\Domain\Model\Document;

interface DocumentRepositoryInterface
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function findAll();

    public function find($id, $lockMode = null, $lockVersion = null);

    public function findOneBy(array $criteria, array $orderBy = null);

    public function save(Document $document): void;

    public function remove(Document $document): void;
}
