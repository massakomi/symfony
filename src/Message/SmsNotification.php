<?php

namespace App\Message;

// https://symfony.com/doc/current/messenger.html

//  (1) a message class that holds data
class SmsNotification
{
    public function __construct(
        private string $content,
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
