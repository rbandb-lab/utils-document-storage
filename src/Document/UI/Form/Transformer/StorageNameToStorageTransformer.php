<?php

declare(strict_types=1);

namespace App\Document\UI\Form\Transformer;

use App\Document\Domain\Repository\StorageRepositoryInterface;
use App\Http\Infra\Exception\BadRequestException;
use Symfony\Component\Form\DataTransformerInterface;

class StorageNameToStorageTransformer implements DataTransformerInterface
{
    private StorageRepositoryInterface $storageRepository;

    public function __construct(StorageRepositoryInterface $storageRepository)
    {
        $this->storageRepository = $storageRepository;
    }

    public function transform($name)
    {
        if (!$name) {
            return;
        }

        return $this->storageRepository->findOneBy([
            'name' => strtolower($name),
        ]);
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            throw BadRequestException::createFromMessage('Nom de stockage inexistant :'.$value);
        }

        return $this->storageRepository->findOneBy(['name' => $value]);
    }
}
