<?php

declare(strict_types=1);

namespace App\Document\App\Command;

class DeleteDocumentCommand
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
