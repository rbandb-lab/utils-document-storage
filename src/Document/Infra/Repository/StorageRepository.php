<?php

declare(strict_types=1);

namespace App\Document\Infra\Repository;

use App\Document\Domain\Model\Storage;
use App\Document\Domain\Repository\StorageRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StorageRepository extends ServiceEntityRepository implements StorageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Storage::class);
    }

    public function save(Storage $storage): void
    {
        $em = $this->getEntityManager();
        $em->persist($storage);
        $em->flush();
    }
}
