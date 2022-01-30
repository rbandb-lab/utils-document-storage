<?php

declare(strict_types=1);

namespace App\Document\App\Command;

use App\Document\Domain\Dto\DocumentUploadDto;
use App\Document\Domain\Model\CfgDocumentType;
use App\Document\Domain\Model\Storage;

class CreateDocumentCommand
{
    private array $payload = [];

    public function __construct(string $id, DocumentUploadDto $dto)
    {
        $this->payload = array_merge([
            'id' => $id,
            ], $dto->toArray());
    }

    public function getId(): string
    {
        return $this->payload['id'];
    }

    public function getDirectory(): ?string
    {
        return $this->payload['directory'];
    }

    public function getName(): ?string
    {
        return $this->payload['name'];
    }

    public function getFile()
    {
        return $this->payload['file'];
    }

    public function getStorageBucket(): Storage
    {
        return $this->payload['storageBucket'];
    }

    public function getDocumentType(): ?CfgDocumentType
    {
        return $this->payload['documentType'];
    }
}
