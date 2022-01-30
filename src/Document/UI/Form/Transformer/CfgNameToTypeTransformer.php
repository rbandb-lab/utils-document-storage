<?php

declare(strict_types=1);

namespace App\Document\UI\Form\Transformer;

use App\Document\Domain\Repository\CfgRepositoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CfgNameToTypeTransformer implements DataTransformerInterface
{
    private CfgRepositoryInterface $cfgRepository;

    public function __construct(CfgRepositoryInterface $cfgRepository)
    {
        $this->cfgRepository = $cfgRepository;
    }

    public function transform($value)
    {
        if (!$value) {
            return '';
        }

        return $this->cfgRepository->findOneBy([
            'name' => strtolower($value),
        ]);
    }

    public function reverseTransform($value)
    {
//        if (!$value) {
//            $privateErrorMessage = 'The given "{{ value }}" value is not a valid config name.';
//            $publicErrorMessage = 'Pas de document type nommé {{ value }}';
//            $failure = new TransformationFailedException($privateErrorMessage);
//            $failure->setInvalidMessage($publicErrorMessage, [
//                '{{ value }}' => $value,
//            ]);
//        }
//
//        if (null === $value) {
//            throw new TransformationFailedException(sprintf('Pas de document type nommé "%s" existant !', $value));
//        }

        $config = null;
        if ($value) {
            $config = $this->cfgRepository->findOneBy(
                [
                    'name' => strtolower($value),
                ]
            );
        }

        return $config;
    }
}
