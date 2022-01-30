<?php

namespace App\Document\Domain\Repository;

use App\Document\Domain\Model\Storage;

interface StorageRepositoryInterface
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function findAll();

    public function find($id, $lockMode = null, $lockVersion = null);

    public function findOneBy(array $criteria, array $orderBy = null);

    public function save(Storage $storage): void;
}
