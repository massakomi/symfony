<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\GreetingGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}