<?php

declare(strict_types=1);

namespace App\Http\Service;

use App\Http\Domain\CloudStorageAdapterInterface;
use App\Http\Infra\Exception\BadRequestException;

class CloudStorageRegistry implements CloudStorageAdapterInterface
{
    private array $adapters;
    private ?CloudStorageAdapterInterface $adapter = null;

    public function __construct(iterable $adapters)
    {
        $this->adapters = iterator_to_array($adapters);
    }

    public function getStorageFor(string $adapterName)
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->support($adapterName)) {
                return $adapter;
            }
        }

        throw new BadRequestException('Storage adapter Class manquant : '.$adapterName);
    }

    public function support(string $adapterName)
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->support($adapterName)) {
                return true;
            }
        }

        return false;
    }

    public function getFile(string $filePath)
    {
    }

    public function postFile(string $filePath, \SplFileInfo $file): bool
    {
    }

    public function deleteFile(string $filePath)
    {
    }
}
