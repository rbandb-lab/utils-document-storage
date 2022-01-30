<?php

declare(strict_types=1);

namespace App\Document\Infra\CloudStorage;

use App\Http\Domain\CloudStorageAdapterInterface;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\File\File;

class AzureBlobStorageAdapter implements CloudStorageAdapterInterface
{
    private FilesystemInterface $azure;
    private LoggerInterface $logger;

    public function __construct(
        FilesystemInterface $azure,
        LoggerInterface $logger
    ) {
        $this->azure = $azure;
        $this->logger = $logger;
    }

    public function support(string $adapterName)
    {
        return 'azure' === strtolower($adapterName);
    }

    public function getFile(string $filePath)
    {
        try {
            return $this->azure->read($filePath);
        } catch (FileNotFoundException $exception) {
            $this->logger->log(LogLevel::DEBUG, $exception->getMessage());
        }
    }

    public function postFile(string $filePath, \SplFileInfo $fileInfo): bool
    {
        $file = new File($fileInfo->getRealPath());
        $result = $this->azure->write($filePath, $file->getContent());
        if (!$result) {
            throw new \Exception('Impossible d\'uploader le fichier '.$file->getFilename().' sur le storage distant');
        }

        return $result;
    }

    public function deleteFile(string $filePath)
    {
        try {
            $this->azure->delete($filePath);
        } catch (FileNotFoundException $exception) {
            $this->logger->log(LogLevel::DEBUG, $exception->getMessage());
        }
    }
}
