<?php

declare(strict_types=1);

namespace App\Document\App\Finder;

use App\Document\App\Query\GetFileQuery;
use App\Document\Domain\Model\Document;
use App\Document\Domain\Repository\DocumentRepositoryInterface;
use App\Http\Infra\Exception\BadRequestException;
use App\Http\Service\CloudStorageRegistry;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;

class GetFileQueryFinder
{
    private DocumentRepositoryInterface $documentRepository;
    private CloudStorageRegistry $cloudStorageRegistry;
    private AdapterInterface $cache;
    private string $localUploadDirectory;
    private string $cacheDirectory;

    public function __construct(
        DocumentRepositoryInterface $documentRepository,
        CloudStorageRegistry $cloudStorageRegistry,
        AdapterInterface $cache,
        string $localUploadDirectory
    ) {
        $this->documentRepository = $documentRepository;
        $this->cloudStorageRegistry = $cloudStorageRegistry;
        $this->cache = $cache;
        $this->localUploadDirectory = $localUploadDirectory;
    }

    public function __invoke(GetFileQuery $query): array
    {
        $document = $this->documentRepository->find($query->getId());
        if (!$document instanceof Document) {
            throw BadRequestException::createFromMessage('Document not found '.$query->getId());
        }
        $fileName = $document->getId().'.'.$document->getExtension();
        $result = [
            'fileName' => $fileName,
            'userFileName' => $document->getFileName(),
            'size' => $document->getSize(),
            'mimeType' => $document->getMimeType(),
        ];

        // try local upload dir [big files]
        $file = $this->findLocalUploadDir($document);
        if ($file instanceof \SplFileInfo) {
            $result['file'] = $file;

            return $result;
        }

        // if already deleted -> try to fetch from cache (fetch from cloud)
        $cache = $this->cache;
        $item = $cache->getItem($fileName);

        if (!$item->isHit()) {
            // try to fetch file from Azure
            $storage = $this->cloudStorageRegistry->getStorageFor($document->getStorage()->getName());
            $targetPath = empty($document->getTargetDir()) ? './'.$fileName : $document->getTargetDir().DIRECTORY_SEPARATOR
                .$fileName;
            $fileStream = $storage->getFile($targetPath);
            $item->set($fileStream);
            $cache->save($item);
        }

        $fileStream = $item->get();
        if (!$fileStream) {
            throw new BadRequestException('File not found', null, Response::HTTP_NOT_FOUND);
        }

        $result['file'] = $fileStream;

        return $result;
    }

    private function findLocalUploadDir(Document $document): ?\SplFileInfo
    {
        $finder = new Finder();
        $finder->in($this->localUploadDirectory)->name($document->getId().'.*');

        if (1 === $finder->count()) {
            $iterator = $finder->getIterator();
            $iterator->rewind();

            return $iterator->current();
        }

        return null;
    }
}
