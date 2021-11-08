<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\GreetingGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Task;
use App\Entity\News;
use App\Form\Type\TaskType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DefaultController extends AbstractController
{
    public function index($name='test', GreetingGenerator $generator)
    {

        $repository = $this->getDoctrine()->getRepository(News::class);
        $news = $repository->findAll();

        //$greeting = $generator->getRandomGreeting();

        return $this->render('default/index.html.twig', [
            'news' => $news,
        ]);
        // return new Response('Hello!');
    }

    /**
     * @Route("/news", name="news")
     */
    public function news()
    {
        return new Response('Simple! Easy! Great!');
    }

    /**
     * @Route("/news/{slug}", name="news_show")
     */
    public function news_show()
    {
        return new Response('Simple! Easy! Great!');
    }

    /**
     * @Route("/implantatia.html")
     */
    public function implantatia()
    {
        return $this->render('default/implantatia.html.twig', [
            
        ]);
    }

    /**
     * {@inheritdoc}
     */
    /*public function error($exception, $logger)
    {
        $res = $this->load404();
        if ($res) {
        	return new Response('Loaded!');
        }
        return new Response($exception->getMessage());
    }*/

    /**
     * {@inheritdoc}
     */
    public function load404()
    {
        $path = substr($_SERVER['REQUEST_URI'], 1);
        $path = preg_replace('~\?.*~i', '', $path);
        if (strpos($path, '.')) {
        	$url = 'https://overclockers.ru/'.$path;
        } else {
            return ;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $content = curl_exec($ch);
        curl_close($ch);

        fwrite($a = fopen('log.txt', 'a+'), "\n".date('Y-m-d H:i:s').' '.$_SERVER['REQUEST_URI']); fclose($a);

        if ($content) {
            $dirs = explode('/', $path);
            $d = '';
            foreach ($dirs as $k => $v) {
                if ($d) {
                    $d .= '/';
                }
            	$d .= $v;
                if (file_exists($d)) {
                    continue;
                }
                if (preg_match('~\..{2,4}$~i', $d)) {
                    continue;
                }
                mkdir($d);
            }

            fwrite($a = fopen(urldecode($path), 'w+'), $content); fclose($a);
        }
        return true;
    }

    /**
     * @Route("/form")
     */
    public function new(Request $request): Response
    {
        // создает объект задачи и инициализирует некоторые данные для этого примера
        $task = new Task();
        //$task->setTask('Write a blog post');
        //$task->setDueDate(new \DateTime('tomorrow'));

        /*$form = $this->createFormBuilder($task)
            ->setAction($this->generateUrl('target_route'))
            ->setMethod('GET')
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();*/

        // используйте некоторую PHP-логику, чтобы решить, обязательно ли это поле формы
        $dueDateIsRequired = true;

        $form = $this->createForm(TaskType::class, $task, [
            'require_due_date' => $dueDateIsRequired,
            //'action' => $this->generateUrl('target_route'),
            //'method' => 'GET',
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // но изначальная переменная `$task` также была обновлена
            $task = $form->getData();

            // ... выполните какое-то действие, например сохраните задачу в базу данных
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('task_success');
        }

        return $this->render('task/new.html.twig', [
            'form' => $form->createView(),
        ]);

        // ...
    }
    /**
     * @Route("/task_success", name="task_success")
     */
    public function task_success(Request $request): Response
    {
        return new Response('Task OK!');
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
