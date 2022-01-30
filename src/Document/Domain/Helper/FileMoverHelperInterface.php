<?php

declare(strict_types=1);

namespace App\Document\Domain\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileMoverHelperInterface
{
    public function move(UploadedFile $file, string $uuid): array;
}
