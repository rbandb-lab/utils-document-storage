<?php

declare(strict_types=1);

namespace App\Document\UI\Action;

use App\Document\App\Command\DeleteDocumentCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteDocumentAction
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request, string $id)
    {
        $this->commandBus->dispatch(new DeleteDocumentCommand($id));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
