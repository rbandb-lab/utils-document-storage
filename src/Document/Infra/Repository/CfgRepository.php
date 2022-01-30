<?php

declare(strict_types=1);

namespace App\Document\Infra\Repository;

use App\Document\Domain\Model\CfgDocumentType;
use App\Document\Domain\Repository\CfgRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CfgRepository extends ServiceEntityRepository implements CfgRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CfgDocumentType::class);
    }
}
