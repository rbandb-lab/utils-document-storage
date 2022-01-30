<?php

declare(strict_types=1);

namespace App\Document\UI\Action;

use App\Document\App\Command\CreateDocumentCommand;
use App\Document\Domain\Dto\UploadFileDtoValidator as fileValidator;
use App\Document\UI\Form\Type\UploadDocumentType;
use App\Http\Infra\Exception\BadRequestException;
use App\Http\Infra\Request\JsonDecodeTrait;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

class CreateDocumentAction
{
    use JsonDecodeTrait;

    private MessageBusInterface $commandBus;
    private FormFactoryInterface $formFactory;
    private fileValidator $fileValidator;
    private string $localUploadDirectory;

    public function __construct(
        FormFactoryInterface $formFactory,
        MessageBusInterface $commandBus,
        fileValidator $fileValidator
    ) {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
        $this->fileValidator = $fileValidator;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $form = $this->formFactory->create(UploadDocumentType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            throw BadRequestException::createFromForm($form);
        }

        $id = Uuid::v4();
        $this->commandBus->dispatch(new CreateDocumentCommand($id->toRfc4122(), $this->fileValidator->handle($form)));

        return new JsonResponse($id, Response::HTTP_OK);
    }
}
