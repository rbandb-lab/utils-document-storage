<?php

declare(strict_types=1);

namespace App\Document\Domain\Dto;

use App\Document\Domain\Model\CfgDocumentType;
use App\Document\Domain\Model\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploadFileDTO.
 */
class DocumentUploadDto
{
    public ?string $name = '';
    public ?string $directory = '/';
    public ?UploadedFile $file = null;
    public ?CfgDocumentType $documentType = null;
    public ?Storage $storage = null;

    public function toArray()
    {
        return [
            'name' => $this->name,
            'file' => $this->file,
            'directory' => $this->directory,
            'documentType' => $this->documentType,
            'storageBucket' => $this->storage,
        ];
    }
}
