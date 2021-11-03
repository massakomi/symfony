<?php

// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/resolver/{id}")
     */
    public function index(User $user)
    {
        return new Response('Hello '.$user->getUsername().'!');
    }
}


