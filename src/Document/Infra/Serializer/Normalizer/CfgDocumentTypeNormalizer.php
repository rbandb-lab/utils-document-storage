<?php

declare(strict_types=1);

namespace App\Document\Infra\Serializer\Normalizer;

use App\Document\Domain\Model\CfgDocumentType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class CfgDocumentTypeNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function normalize($object, string $format = null, array $context = [])
    {
        /* @var CfgDocumentType $object */
        return [
          'id' => $object->getId(),
          'name' => $object->getName(),
        ];
    }

    public function supportsNormalization($data, string $format = null)
    {
        return $data instanceof CfgDocumentType && 'json' === $format;
    }
}
