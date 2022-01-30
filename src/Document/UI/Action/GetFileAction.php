<?php

declare(strict_types=1);

namespace App\Document\UI\Action;

use App\Document\App\Query\GetFileQuery;
use App\Http\Infra\Request\EnveloppeTrait;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\String\UnicodeString;

class GetFileAction
{
    use EnveloppeTrait;

    private MessageBusInterface $queryBus;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    public function __invoke(Request $request, string $id): Response
    {
        $query = $this->queryBus->dispatch(new GetFileQuery($id));
        $result = $this->handle($query);
        $fileContent = $result['file'];

        if ($fileContent instanceof \SplFileInfo) {
            $response = new BinaryFileResponse($fileContent->openFile('r+'));
        } else {
            $response = new StreamedResponse(function () use ($fileContent) {
                echo $fileContent;
            });
        }

        $fileName = $result['userFileName'] ?? $result['fileName'];
        $asciiFileName = new UnicodeString($fileName);

        $response->headers->set('Content-Disposition', HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_INLINE,
            $fileName,
            (string) $asciiFileName->ascii()
        ));

        $response->headers->set('Content-type', $result['mimeType']);
        $response->headers->set('Content-length', $result['size']);

        return $response;
    }
}
