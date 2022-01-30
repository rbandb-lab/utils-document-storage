<?php

declare(strict_types=1);

namespace App\Http\Infra\Exception\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class ExceptionListener
{
    /** @var string */
    private $environment;

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $exception = $exception instanceof HandlerFailedException ? $exception->getPrevious() : $exception;
        $code = ($exception instanceof HttpException) ? $exception->getStatusCode() : 500;
        $message = $this->resolveExceptionMessage($exception);
        if (null !== ($decodedMessage = json_decode($message, true))) {
            $message = $decodedMessage;
        }
        $response = new JsonResponse([
            'error_message' => $message,
            'status_code' => $code,
            ],
            $code
        );

        $event->setResponse($response);
    }

    /**
     * Hide error 5xx messages in prod environment.
     *
     * @return string
     */
    protected function resolveExceptionMessage(\Throwable $exception)
    {
        return 'prod' === $this->environment && $exception->getCode() >= 500
            ? 'Internal server error'
            : $exception->getMessage();
    }
}
