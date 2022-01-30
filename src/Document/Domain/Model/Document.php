<?php

declare(strict_types=1);

namespace App\Document\Domain\Model;

class Document
{
    private string $id;
    private ?CfgDocumentType $type;
    private Storage $storage;
    private string $extension;
    private int $size;
    private ?string $mimeType;
    private ?string $fileName;
    private ?string $targetDir;

    public function __construct(
        string $id,
        ?CfgDocumentType $type,
        Storage $storage,
        string $extension,
        int $size,
        ?string $mimeType,
        ?string $fileName,
        ?string $targetDir
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->fileName = $fileName;
        $this->storage = $storage;
        $this->targetDir = $targetDir;
        $this->extension = $extension;
        $this->size = $size;
        $this->mimeType = $mimeType;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getType(): ?CfgDocumentType
    {
        return $this->type;
    }

    public function setType(?CfgDocumentType $type): void
    {
        $this->type = $type;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }

    public function setStorage(Storage $storage): void
    {
        $this->storage = $storage;
    }

    public function getFileName(): ?string
    {
        return $this->fileName ?? $this->id;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getTargetDir(): ?string
    {
        return $this->targetDir;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }
}
