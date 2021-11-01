<?php

// src/Controller/ProductController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="create_product")
     */
    public function createProduct(ValidatorInterface $validator): Response
    {

        $this->test();

var_dump(1); exit;

        // вы можете извлечь EntityManager через $this->getDoctrine()
        // или вы можете добавить к действию аргумент: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName(null);
        $product->setPrice('1212');
        //$product->setDescription('Ergonomic and stylish!');

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        // сообщите Doctrine, что вы хотите (в итоге) сохранить Продукт (пока без запросов)
        $entityManager->persist($product);

        // действительно выполните запросы (например, запрос INSERT)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show(int $id): Response
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('Check out this great product: '.$product->getName());

        // или отобразить шаблон
        // в шаблоне, печатайте все с {{ product.name }}
        // вернет $this->render('product/show.html.twig', ['product' => $product]);
    }

    /**
     * {@inheritdoc}
     */
    public function test()
    {
        $id = 2;

        $repository = $this->getDoctrine()->getRepository(Product::class);

        // искать один Продукт по его основному ключу (обычно "id")
        $product = $repository->find($id);

        // искать один Продукт по имени
        $product = $repository->findOneBy(['name' => 'Keyboard']);
        // или по имени и цене
        $product = $repository->findOneBy([
            'name' => 'Keyboard',
            'price' => 1999,
        ]);

        // искать несколько объектов Продуктов соответствующих имени, упорядоченные по цене
        $products = $repository->findBy(
            ['name' => 'Keyboard'],
            ['price' => 'ASC']
        );

        // искать *все* объекты Продуктов
        $products = $repository->findAll();
    }
}