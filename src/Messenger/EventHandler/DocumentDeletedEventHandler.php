<?php

declare(strict_types=1);

namespace App\Messenger\EventHandler;

use App\Document\Domain\Repository\DocumentRepositoryInterface;
use App\Document\Domain\Repository\StorageRepositoryInterface;
use App\Http\Domain\CloudStorageAdapterInterface;
use App\Http\Service\CloudStorageRegistry;
use App\Messenger\Event\DocumentDeletedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Finder\Finder;

class DocumentDeletedEventHandler
{
    private LoggerInterface $logger;
    private CloudStorageRegistry $registry;
    private string $localUploadDirectory;
    private string $cacheDirectory;
    private StorageRepositoryInterface $storageRepository;
    private DocumentRepositoryInterface $documentRepository;

    public function __construct(
        LoggerInterface $logger,
        DocumentRepositoryInterface $documentRepository,
        StorageRepositoryInterface $storageRepository,
        CloudStorageRegistry $registry,
        string $localUploadDirectory,
        string $cacheDirectory
    ) {
        $this->logger = $logger;
        $this->registry = $registry;
        $this->localUploadDirectory = $localUploadDirectory;
        $this->cacheDirectory = $cacheDirectory;
        $this->storageRepository = $storageRepository;
        $this->documentRepository = $documentRepository;
    }

    public function __invoke(DocumentDeletedEvent $event)
    {
        $fileName = $event->getId().'.'.$event->getExtension();

        // delete into cloud storage
        /** @var CloudStorageAdapterInterface $adapter */
        $adapter = $this->registry->getStorageFor($event->getStorage());
        $filePath = $event->getTargetDir().DIRECTORY_SEPARATOR.$fileName;
        $adapter->deleteFile($filePath);

//        // delete cached item
        $cache = new FilesystemAdapter(
            $namespace = '',
            $defaultLifetime = 1800,
            $directory = $this->cacheDirectory.DIRECTORY_SEPARATOR.'files'
        );

        $item = $cache->getItem($fileName);
        $item->expiresAfter(0);
        $cache->save($item);
        $cache->prune();

        // delete file on upload directory
        $finder = new Finder();
        $finder->in($this->localUploadDirectory)->in($this->cacheDirectory)->name($fileName);
        foreach ($finder as $file) {
            if ($file instanceof \SplFileInfo) {
                unlink($file->getPathname());
            }
        }
    }
}
