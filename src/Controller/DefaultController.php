<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\GreetingGenerator;
use App\Service\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\ErrorRenderer\ErrorRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Comment;
use App\Entity\Task;
use App\Entity\News;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DefaultController extends AbstractController
{


    /**
     * @Route("/", name="index")
     */
    public function index(Utils $utils)
    {
        //func();

        return $this->render('default/index.html.twig', [

        ]);
    }


    public function indexOverclockers()
    {
        $repository = $this->getDoctrine()->getRepository(News::class);
        $news = $repository->findAll();

        $repository = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $repository->findAll();

        return $this->render('default/over.html.twig', [
            'news' => $news,
            'comments' => $comments,
        ]);
    }


    /**
     * @Route("/news", name="news")
     */
    public function news()
    {
        return new Response('Simple! Easy! Great!');
    }

    /**
     * @Route("/news/{slug}", name="news_item")
     */
    public function news_item(News $news)
    {
        return $this->render('news/one.html.twig', [
            'item' => $news,
        ]);
    }

}
