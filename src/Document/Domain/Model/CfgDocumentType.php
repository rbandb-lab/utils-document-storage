<?php

declare(strict_types=1);

namespace App\Document\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CfgDocumentType
{
    private string $id;

    private string $name;

    private int $sizeLimitInKb;

    private array $allowedExtensions;

    private Collection $documents;

    public function __construct(
        string $id,
        string $name,
        array $allowedExtensions,
        ?int $sizeLimitInKb
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->allowedExtensions = $allowedExtensions;
        $this->documents = new ArrayCollection();
        $this->sizeLimitInKb = $sizeLimitInKb ?? 30270;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSizeLimitInKb(): int
    {
        return $this->sizeLimitInKb;
    }

    public function getAllowedExtensions(): array
    {
        return $this->allowedExtensions;
    }
}
