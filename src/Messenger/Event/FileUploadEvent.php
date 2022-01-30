<?php

declare(strict_types=1);

namespace App\Messenger\Event;

use App\Document\Domain\Model\Document;

class FileUploadEvent
{
    private Document $document;
    private string $path;

    public function __construct(Document $document, string $path)
    {
        $this->document = $document;
        $this->path = $path;
    }

    public function getDocument(): Document
    {
        return $this->document;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
