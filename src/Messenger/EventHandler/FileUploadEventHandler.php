<?php

declare(strict_types=1);

namespace App\Messenger\EventHandler;

use App\Http\Service\CloudStorageRegistry;
use App\Messenger\Event\DeleteLocalFileEvent;
use App\Messenger\Event\FileUploadEvent;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class FileUploadEventHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private CloudStorageRegistry $registry;
    private MessageBusInterface $eventBus;

    public function __construct(
        LoggerInterface $logger,
        CloudStorageRegistry $registry,
        MessageBusInterface $eventBus
    ) {
        $this->logger = $logger;
        $this->registry = $registry;
        $this->eventBus = $eventBus;
    }

    public function __invoke(FileUploadEvent $event): void
    {
        $document = $event->getDocument();
        $adapter = $this->registry->getStorageFor($storageName = $document->getStorage()->getName());
        $fileInfo = new \SplFileInfo($event->getPath());
        $destinationPath = $document->getTargetDir().DIRECTORY_SEPARATOR.$document->getId().'.'.$fileInfo->getExtension();
        $status = $adapter->postFile($destinationPath, $fileInfo);
        if (!$status) {
            $this->logger->critical('Could not upload document .'.$document->getId().' to destination : '.$destinationPath.' on Storage '.$storageName);
        }

        $this->logger->log(LogLevel::INFO, 'file uploaded');
        $this->eventBus->dispatch(new DeleteLocalFileEvent($fileInfo->getFilename()));
    }
}
