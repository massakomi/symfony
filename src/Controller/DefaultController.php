<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Comment;
use App\Entity\News;

class DefaultController extends AbstractController
{


    /**
     * Данные для рендера
     */
    public function params()
    {
        $user = $this->getUser();
        return [
            'userId' => $user?->getId()
        ];
    }

    /**
     * index
     * @Route("/", name="catalog")
     */
    public function index(ManagerRegistry $doctrine)
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $repository = $doctrine->getRepository(Product::class);
        $products = $repository->findAll();
        return $this->render('catalog/products.html.twig', [
            'products' => $products
        ] + $this->params());
    }

    /**
     * index
     * @Route("/product/{slug}/", name="product")
     */
    public function productPage(ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository(Product::class);
        $products = $repository->findAll();

        return $this->render('catalog/products.html.twig', [
            'products' => $products
        ] + $this->params());
    }

    /**
     * Overclockers index
     * @Route("/overclockers")
     */
    public function overclockers(ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository(News::class);
        $news = $repository->findAll();

        $repository = $doctrine->getRepository(Comment::class);
        $comments = $repository->findAll();

        return $this->render('overclockers/index.html.twig', [
            'news' => $news,
            'comments' => $comments,
        ] + $this->params());
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
        return $this->render('overclockers/news-one.html.twig', [
            'item' => $news,
        ]);
    }

}
