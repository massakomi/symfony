<?php

namespace App\Message;

use App\Message\SmsNotification;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\StopWorkerException;

// (2) a handler(s) class that will be called when that message is dispatched.
// проверить, что класс привязался к отправителю - php bin/console debug:messenger
#[AsMessageHandler]
class SmsNotificationHandler
{
    public function __invoke(SmsNotification $message)
    {
        $filesystem = new Filesystem();
        $filesystem->appendToFile(dirname(__FILE__).'/sms.txt', "\r\n".date('Y-m-d H:i:s').' '.$message->getContent());
        // ... do some work - like sending an SMS message!
        throw new StopWorkerException('Завершаем');
    }
}
