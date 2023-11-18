<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Reviews;
use App\Form\ReviewsType;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Comment;
use App\Entity\News;

class DefaultController extends AbstractController
{

    public $breadcrumbs = [];

    /**
     * Данные для рендера
     */
    public function params()
    {
        $user = $this->getUser();
        return [
            'userId' => $user?->getId(),
            'breadcrumbs' => $this->breadcrumbs
        ];
    }

    /**
     * index
     * @Route("/", name="catalog")
     */
    public function index(ManagerRegistry $doctrine, LoggerInterface $logger)
    {
        $this->breadcrumbs []= 'Оформление заказа';
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $repository = $doctrine->getRepository(Product::class);
        $products = $repository->findBy(['active' => true]);
        return $this->render('catalog/products.html.twig', [
            'products' => $products
        ] + $this->params());
    }

    /**
     * index
     * @Route("/product/{id}/", name="product")
     */
    public function productPage(ManagerRegistry $doctrine, Request $request, int $id)
    {
        $repository = $doctrine->getRepository(Product::class);
        $product = $repository->find($id);

        $reviews = $this->getReviews($id);
        $form = $this->createForm(ReviewsType::class, new Reviews(), [
            'action' => $this->generateUrl('review', ['id' => $id]),
            'method' => 'POST',
        ]);

        $this->breadcrumbs []= $product->getName();

        return $this->render('catalog/product.html.twig', [
                'product' => $product,
                'reviews' => $reviews,
                'form' => $form->createView(),
            ] + $this->params());
    }

    /**
     * Хрень, надо куда-то выносить. вероятно в репозиторий
     */
    public function getReviews($id)
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        //$redis->del('review-'.$id);
        //$redis->expire('review-'.$id, 3);

        $reviews = $redis->lRange('review-'.$id, 0, -1);
        foreach ($reviews as $k => $v) {
            $reviews [$k] = unserialize($v);
        }
        return $reviews;
    }

    /**
     * @Route("/product/review/{id}/", name="review", methods={"POST"})
     */
    public function review(Request $request, int $id)
    {
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $user = $this->getUser();
        if ($user) {
            $item = new Reviews();
            $item->setUserId($user->getId());
            $item->setUsername($user->getUsername());
            $form = $this->createForm(ReviewsType::class, $item);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $item = $form->getData();
                $redis->rPush('review-'.$id, serialize($item));
                $this->addFlash('success', 'Отзыв добавлен');
            } else {
                foreach ($form->getErrors() as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }
            return $this->redirectToRoute('product', ['id' => $id]);
        }
        throw new \Exception('Нет доступа');
    }

    /**
     * index
     * @Route("/order", name="order")
     */
    public function order(RequestStack $requestStack, ManagerRegistry $doctrine)
    {
        return $this->render('catalog/order.html.twig', [
            //'products' => $products
        ] + $this->params());
    }

    /**
     * Эта помойка тоже все в одной куче...
     * @Route("/basket/{mode}", name="basket")
     */
    public function basket(RequestStack $requestStack, ManagerRegistry $doctrine, string $mode = '', int $id = 0)
    {
        $request = $requestStack->getCurrentRequest();
        $session = $requestStack->getSession();
        $basket = $session->get('basket');
        if (!$basket) {
            $basket = [];
        }
        if ($mode == 'add') {
            $id = $request->query->get('id');
            if (!$id) {
                return $this->json(['error' => 'empty id']);
            }
            $quantity = $request->query->get('quantity') > 0 ? $request->query->get('quantity') : 1;
            if (!isset($basket [$id])) {
                $basket [$id] = $quantity;
            } else {
                $basket [$id] += $quantity;
            }
            $session->set('basket', $basket);
            return $this->json(['success' => true]);
        }
        if ($mode == 'update') {
            $id = $request->query->get('id');
            if (!$id) {
                return $this->json(['error' => 'empty id']);
            }
            if (!isset($basket [$id])) {
                return $this->json(['error' => 'basket item not found']);
            }
            $quantity = (int)$request->query->get('quantity');
            if ($quantity < 1) {
                return $this->json(['error' => 'empty quantity']);
            }
            $basket [$id] = $quantity;
            $session->set('basket', $basket);
            return $this->json(['success' => true]);
        }
        if ($mode == 'delete') {
            $id = $request->query->get('id');
            if (!$id) {
                return $this->json(['error' => 'empty id']);
            }
            unset($basket [$id]);
            $session->set('basket', $basket);
            return $this->redirectToRoute('basket');
        }
        $total = 0;
        if ($basket) {
            $repository = $doctrine->getRepository(Product::class);
            $products = $repository->findBy([
                'id' => array_keys($basket)
            ]);
            foreach ($products as $item) {
                $quantity = $basket[$item->getId()];
                $sum = $quantity * $item->getPrice();
                $basket [$item->getId()] = [];
                $basket [$item->getId()]['product'] = $item;
                $basket [$item->getId()]['quantity'] = $quantity;
                $basket [$item->getId()]['sum'] = $sum;
                $total += $sum;
            }
        }
        $this->breadcrumbs []= 'Корзина';

        return $this->render('catalog/basket.html.twig', [
                'basket' => $basket,
                'total' => $total,
            ] + $this->params());
    }

}
