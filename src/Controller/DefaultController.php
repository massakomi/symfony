<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\GreetingGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class DefaultController extends AbstractController
{
    public function index($name='test', GreetingGenerator $generator)
    {

        $greeting = $generator->getRandomGreeting();

        return $this->render('default/index.html.twig', [
            'name' => $name.'*'.$greeting,
        ]);
        // return new Response('Hello!');
    }

    /**
     * @Route("/simplicity")
     */
    public function simple()
    {
        return new Response('Simple! Easy! Great!');
    }


   /**
    * @Route("/cache/")
    */
    public function cache()
    {

        $pool = new FilesystemAdapter();

        // Функция будет запущена только при отсутствии значения в кэше
        $value = $pool->get('my_cache_key', function (ItemInterface $item) {
            $item->expiresAfter(3600);

            // ... сделать HTTP запрос или сложные вычисления
            $computedValue = 'foobar '.date('Y-m-d H:i:s');

            return $computedValue;
        });

        echo $value; // 'foobar'

        // ... и удалить значение кэша по ключу
        //$pool->delete('my_cache_key');

        return new Response('Hello!');
    }

}
