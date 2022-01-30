<?php

declare(strict_types=1);

namespace App\Document\Infra\Repository;

use App\Document\Domain\Model\Document;
use App\Document\Domain\Repository\DocumentRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DocumentRepository extends ServiceEntityRepository implements DocumentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function save(Document $document): void
    {
        $em = $this->getEntityManager();
        $em->persist($document);
        $em->flush();
    }

    public function remove(Document $document): void
    {
        $em = $this->getEntityManager();
        $em->remove($document);
        $em->flush();
    }
}
