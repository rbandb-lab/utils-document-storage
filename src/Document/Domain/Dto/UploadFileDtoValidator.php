<?php

declare(strict_types=1);

namespace App\Document\Domain\Dto;

use App\Document\Domain\Model\CfgDocumentType;
use App\Document\Domain\Model\Storage;
use Doctrine\Migrations\Configuration\Exception\FileNotFound;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UploadFileDtoValidator
{
    public function handle(FormInterface $form)
    {
        $uploadFileDTO = $form->getData();
        $uploadedTempFile = $uploadFileDTO->file;

        if (false === $uploadedTempFile instanceof UploadedFile) {
            throw new FileNotFound('No file uploaded into the request ');
        }

        // validate documentType
        $documentType = $uploadFileDTO->documentType;
//        if (!$documentType instanceof CfgDocumentType) {
//            throw new BadRequestHttpException("Ce type de document n'est pas pris en charge : ".$documentType);
//        }

        // validate destination storage name
        $storageBucket = $uploadFileDTO->storage;
        if (!$storageBucket instanceof Storage) {
            throw new BadRequestHttpException('Le stockage specifie est inexistant : '.$storageBucket);
        }

        // validate file extension
//        $fileExtension = $uploadedTempFile->guessExtension();
//        $allowedExtensions = $documentType->getAllowedExtensions();
//        if (false === in_array($fileExtension, $allowedExtensions, true)) {
//            throw new BadRequestHttpException('Seul le(s) format(s) suivants sont acceptés : '.implode(',', $allowedExtensions), null, 400);
//        }

        // validate file size is ok
//        if ($uploadedTempFile->getSize() > $documentType->getSizeLimitInKb() * 1024) {
//            throw new BadRequestHttpException('La limite de taille de fichier est de '.$documentType->getSizeLimitInKb() / 1024 .' MB et celle de votre fichier de :'.$uploadedTempFile->getSize() / 1000000, null, 400);
//        }

        return $uploadFileDTO;
    }
}
