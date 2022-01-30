<?php

declare(strict_types=1);

namespace App\Messenger\Event;

class DocumentDeletedEvent
{
    private array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function getId(): string
    {
        return $this->payload['id'];
    }

    public function getExtension(): string
    {
        return $this->payload['extension'];
    }

    public function getStorage(): string
    {
        return $this->payload['storage'];
    }

    public function getTargetDir(): string
    {
        return $this->payload['targetDir'];
    }
}
