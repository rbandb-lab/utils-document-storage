<?php

declare(strict_types=1);

namespace App\Document\Infra\Serializer\Normalizer;

use App\Document\Domain\Model\Document;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class DocumentNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    private CfgDocumentTypeNormalizer $cfgDocumentTypeNormalizer;
    private StorageNormalizer $storageNormalizer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        CfgDocumentTypeNormalizer $cfgDocumentTypeNormalizer,
        StorageNormalizer $storageNormalizer,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->cfgDocumentTypeNormalizer = $cfgDocumentTypeNormalizer;
        $this->storageNormalizer = $storageNormalizer;
        $this->urlGenerator = $urlGenerator;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        /* @var Document $object */
        return [
            'id' => $object->getId(),
            'storage' => $this->storageNormalizer->normalize($object->getStorage(), 'json'),
            'type' => $this->cfgDocumentTypeNormalizer->normalize($object->getType(), 'json'),
            'fileName' => $object->getFileName(),
            'fileExtension' => $object->getExtension(),
            'targetDir' => $object->getTargetDir(),
            'size' => $object->getSize(),
            'url' => $this->urlGenerator->generate('get_document', ['id' => $object->getId()]),
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof Document && 'json' === $format;
    }
}
