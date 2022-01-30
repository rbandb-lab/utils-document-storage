<?php

declare(strict_types=1);

namespace App\Document\Infra\Helper;

use App\Document\Domain\Helper\FileMoverHelperInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileMoverHelper implements FileMoverHelperInterface
{
    private string $localUploadDirectory;

    public function __construct(string $localUploadDirectory)
    {
        $this->localUploadDirectory = $localUploadDirectory;
    }

    public function move(UploadedFile $file, string $uuid): array
    {
        $fileExtension = $file->guessExtension();
        try {
            $newFileName = $uuid.'.'.$fileExtension;
            $file->move($this->localUploadDirectory, $newFileName);
        } catch (\Exception $e) {
            throw new \Exception('Could not move files to destination : '.$this->localUploadDirectory.DIRECTORY_SEPARATOR.$uuid.'.'.$fileExtension.' '.$e->getMessage());
        }

        $path = $this->localUploadDirectory.DIRECTORY_SEPARATOR.$newFileName;

        return [
            'path' => $path,
            'extension' => $fileExtension,
            'fileInfo' => new \SplFileInfo($path),
        ];
    }
}
