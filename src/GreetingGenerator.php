<?php
// src/GreetingGenerator.php
namespace App;

/*
Это пример простого класса, его можно подключить через хинт в контроллере уже инициализированный
use App\GreetingGenerator;

public function indexTest(GreetingGenerator $generator)
{
    $greeting = $generator->getRandomGreeting();
}

*/

class GreetingGenerator
{
    public function getRandomGreeting()
    {
        $greetings = ['Hey', 'Yo', 'Aloha'];
        $greeting = $greetings[array_rand($greetings)];

        return $greeting;
    }
}