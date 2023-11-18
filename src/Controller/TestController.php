<?php

// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\News;
use App\Entity\Product;
use App\Message\SmsNotification;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TestController extends AbstractController
{


    /**
     * @Route("/rabbitmq", name="rabbitmq")
     */
    public function rabbitmq(MessageBusInterface $bus)
    {

        // will cause the SmsNotificationHandler to be called
        $bus->dispatch(new SmsNotification('Look! I created a message!'));

        return $this->json([true]);
    }

    /**
     * @Route("/design", name="design")
     */
    public function design()
    {
        return $this->render('test/design.html.twig');
    }

    /**
     * @Route("/routes", name="routes")
     */
    public function indexTest(RouterInterface $router)
    {
        $routes = $router->getRouteCollection();
        $content = [];
        $contentAdmin = [];
        foreach ($routes as $route) {
            $path = $route->getPath();
            $controller = $route->getDefaults()['_controller'];
            if (str_starts_with($path, '/_')) {
                continue;
            }
            $url = preg_replace('~\{.*\}~', 1, $path);
            $link = '<a href="'.$url.'" title="'.$controller.'" target="_blank">'.$path.'</a>';
            if (str_starts_with($path, '/admin')) {
                $contentAdmin []= $link;
            } else {
                $content []= $link;
            }
        }
        asort($content);
        asort($contentAdmin);
        $content = implode('<br />', array_unique($content));
        $contentAdmin = implode('<br />', $contentAdmin);
        return $this->render('test/routes.html.twig', compact('content', 'contentAdmin'));

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
    public function testSql(ManagerRegistry $doctrine)
    {


        $entityManager = $doctrine->getManager();
        $conn = $entityManager->getConnection();

        $sql = '
            SELECT * FROM product p
            WHERE p.price > :price
            ORDER BY p.price ASC
            ';
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['price' => 1]);

        //new \Doctrine\DBAL\Statement();

        // returns an array of arrays (i.e. a raw data set)
        return $result->fetchAllAssociative();
    }

    /**
     * @Route("/product", name="create_product")
     */
    public function createProduct(ValidatorInterface $validator, ManagerRegistry $doctrine): Response
    {

        $this->testSql($doctrine);

        // вы можете извлечь EntityManager через $this->getDoctrine()
        // или вы можете добавить к действию аргумент: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName('name');
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


