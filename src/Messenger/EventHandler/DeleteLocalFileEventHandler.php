<?php

declare(strict_types=1);

namespace App\Messenger\EventHandler;

use App\Messenger\Event\DeleteLocalFileEvent;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteLocalFileEventHandler implements MessageHandlerInterface
{
    private string $localUploadDirectory;

    public function __construct(string $localUploadDirectory)
    {
        $this->localUploadDirectory = $localUploadDirectory;
    }

    public function __invoke(DeleteLocalFileEvent $event)
    {
        $finder = new Finder();
        $finder->in($this->localUploadDirectory)->name($event->getFileName());
        foreach ($finder as $file) {
            if ($file instanceof \SplFileInfo) {
                unlink($file->getPathname());
            }
        }
    }
}
