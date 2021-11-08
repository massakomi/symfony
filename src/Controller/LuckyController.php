<?php

// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\News;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

class LuckyController extends AbstractController
{
    /**
     * @Route("/lucky/number/{max}", name="app_lucky_number")
     */
    public function number(int $max): Response
    {

        $url = $this->generateUrl('app_lucky_number', ['max' => 10]);

        $number = random_int(0, $max);

        return new Response(
            '<html><body>Lucky <a href="'.$url.'">number</a>: '.$number.'</body></html>'
        );
    }


    /**
     * @Route("/logger/")
     */
    public function logger(LoggerInterface $logger)
    {

    throw new \Exception('Something went wrong!');

        $logger->debug('debug');
        $logger->info('I just got the logger');
        $logger->error('An error occurred');

        $logger->critical('I left the oven on!', [
            // include extra "context" info in your logs
            'cause' => 'in_hurry',
        ]);


        return new Response('1');
    }


    /**
     * @Route("/db-test/")
     */
    public function dbtest()
    {

        $repository = $this->getDoctrine()->getRepository(News::class);

        // массив всех объектов App\Entity\News Object
        $data = $repository->findAll();

        echo '<pre>'.print_r($data, 1).'</pre>';

        return new Response('1');
    }
}


