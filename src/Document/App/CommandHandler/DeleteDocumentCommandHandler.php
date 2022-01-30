<?php

declare(strict_types=1);

namespace App\Document\App\CommandHandler;

use App\Document\App\Command\DeleteDocumentCommand;
use App\Document\Domain\Model\Document;
use App\Document\Domain\Repository\DocumentRepositoryInterface;
use App\Messenger\Event\DocumentDeletedEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteDocumentCommandHandler
{
    private DocumentRepositoryInterface $repository;
    private MessageBusInterface $evenBus;

    public function __construct(
        DocumentRepositoryInterface $repository,
        MessageBusInterface $evenBus
    ) {
        $this->repository = $repository;
        $this->evenBus = $evenBus;
    }

    public function __invoke(DeleteDocumentCommand $command)
    {
        $document = $this->repository->find($command->getId());

        if (!$document instanceof Document) {
            throw new \Exception('Document Not Found', Response::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $document->getId(),
            'extension' => $document->getExtension(),
            'storage' => $document->getStorage()->getName(),
            'targetDir' => $document->getTargetDir(),
        ];

        $this->repository->remove($document);
        $this->evenBus->dispatch(new DocumentDeletedEvent($data));
    }
}
