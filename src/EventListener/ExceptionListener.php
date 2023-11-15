<?php

// src/EventListener/ExceptionListener.php
namespace App\EventListener;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use InvalidArgumentException;
use App\Service\Utils;

class ExceptionListener implements LoggerAwareInterface
{
    private LoggerInterface $logger;

    public function onKernelException(ExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();

        /*if (get_class($exception) == 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException') {
            Utils::load404('https://hotelizmaelovo.ru/');
        }*/
        /*$message = sprintf(
            'My Error says: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($message);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);*/
    }

    /**
     * {@inheritdoc}
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if ($event->isMainRequest()) {
            $request = $event->getRequest();
            $uri = $request->getRequestUri();
            if (strpos($uri, '/_wdt') !== 0) {
                $this->logger->info($uri);
            }
        }
    }
}
