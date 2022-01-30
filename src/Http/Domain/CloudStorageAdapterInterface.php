<?php

namespace App\Http\Domain;

interface CloudStorageAdapterInterface
{
    public function support(string $adapterName);

    public function getFile(string $filePath);

    public function postFile(string $filePath, \SplFileInfo $file): bool;

    public function deleteFile(string $filePath);
}
