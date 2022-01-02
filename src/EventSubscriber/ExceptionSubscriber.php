<?php

// src/EventSubscriber/ExceptionSubscriber.php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            KernelEvents::EXCEPTION => [
                ['t1', 10],
                ['t2', 0],
                ['t3', -10],
            ],
        ];
    }

    public function t1(ExceptionEvent $event)
    {
        //echo '[t1 метод]';
    }

    public function t2(ExceptionEvent $event)
    {
        //echo '[t2 метод]';
    }

    public function t3(ExceptionEvent $event)
    {
        //echo '[t3 метод]';
    }
}

