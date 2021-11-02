<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\GreetingGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Task;
use App\Form\Type\TaskType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
     * @Route("/test")
     */
    public function test()
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
     * @Route("/form")
     */
    public function new(Request $request): Response
    {
        // создает объект задачи и инициализирует некоторые данные для этого примера
        $task = new Task();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));

        /*$form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Task'])
            ->getForm();*/


        $form = $this->createForm(TaskType::class, $task);

        return $this->render('task/new.html.twig', [
            'form' => $form->createView(),
        ]);

        // ...
    }
}
