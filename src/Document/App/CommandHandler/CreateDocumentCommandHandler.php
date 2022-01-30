<?php

declare(strict_types=1);

namespace App\Document\App\CommandHandler;

use App\Document\App\Command\CreateDocumentCommand;
use App\Document\Domain\Helper\FileMoverHelperInterface;
use App\Document\Domain\Model\Document;
use App\Document\Domain\Model\Storage;
use App\Document\Domain\Repository\DocumentRepositoryInterface;
use App\Messenger\Event\FileUploadEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\MimeTypes;

class CreateDocumentCommandHandler
{
    private DocumentRepositoryInterface $repository;
    private FileMoverHelperInterface $fileMover;
    private MessageBusInterface $eventBus;

    public function __construct(
        DocumentRepositoryInterface $repository,
        FileMoverHelperInterface $fileMover,
        MessageBusInterface $eventBus
    ) {
        $this->repository = $repository;
        $this->fileMover = $fileMover;
        $this->eventBus = $eventBus;
    }

    public function __invoke(CreateDocumentCommand $command)
    {
        $file = $this->fileMover->move($command->getFile(), $id = $command->getId());
        $fileInfo = $file['fileInfo'];

        $mimeTypes = new MimeTypes();
        $mimeTypes->guessMimeType($fileInfo->getPathname());

        $fileExtension = $file['extension'];
        $userFileName = null !== $command->getName()
            ? strtok($command->getName(), '.').'.'.$fileExtension : null;

        $document = new Document(
            $command->getId(),
            $command->getDocumentType(),
            $command->getStorageBucket(),
            $fileExtension,
            (int) round($fileInfo->getSize(), 0),
            $mimeTypes->guessMimeType($fileInfo->getPathname()),
            $userFileName,
            $command->getDirectory()
        );

        $this->repository->save($document);

        // event to upload asynchronously to cloud Storage and delete local file
        $this->eventBus->dispatch(new FileUploadEvent($document, $file['path']));
    }
}
