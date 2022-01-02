<?php

// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\News;
use App\Entity\Product;
use App\GreetingGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TestController extends AbstractController
{


    /**
     * @Route("/routes")
     */
    public function indexTest($name='test', GreetingGenerator $generator)
    {

        $router = $this->get('router');
        $routes = $router->getRouteCollection();
        $content = '';
        foreach ($routes as $route) {
            $content .= '<br /><a href="'.$route->getPath().'" target="_blank">'.$route->getPath().'</a>';
            //var_dump($route->getPath());
            //echo '<pre>'; print_r($route->getOptions()); echo '</pre>';
            //echo '<pre>'; print_r($route->getDefaults()); echo '</pre>';
            //$this->convertController($route);
        }

        //$greeting = $generator->getRandomGreeting();

        /*return $this->render('default/index.html.twig', [
            'content' => $content
        ]);*/
        return new Response($content);

    }

    /**
     * @Route("/http-client")
     */
    public function http_client(HttpClientInterface $client)
    {
        $this->client = $client;

        $response = $this->client->request(
            'GET',
            'https://api.github.com/repos/symfony/symfony-docs'
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        echo '<pre>'.print_r($content, 1).'</pre>';
    }



    /**
     * {@inheritdoc}
     */
    public function testSql()
    {


        $entityManager = $this->getDoctrine()->getManager();
        $conn = $entityManager->getConnection();



        exit;

        $sql = '
            SELECT * FROM product p
            WHERE p.price > :price
            ORDER BY p.price ASC
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['price' => $price]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }

    /**
     * @Route("/product", name="create_product")
     */
    public function createProduct(ValidatorInterface $validator): Response
    {

        $this->testSql();

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
    
    /**
     * @Route("/mailsample/send", name="mail_sample_send")
     */
    public function MailerSample() {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('someone@gmail.com')
            ->setTo('anotherone@gmail.com')
            ->setBody(
                $this->renderView('Emails/sample.html.twig'), 'text/html' );

        $this->get('mailer')->send($message);
        return new Response("Mail send");
    }
}


