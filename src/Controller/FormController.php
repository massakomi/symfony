<?php


namespace App\Controller;


use App\Entity\Task;
use App\Form\Type\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormController extends AbstractController
{

    /**
     * @Route("/valid-test")
     */
    public function testValid(ValidatorInterface $validator)
    {
        $task = new Task();
        $task->emptyVal = 1;
        $task->onlyInts = '1';
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));

        // ... do something to the $author object

        $errors = $validator->validate($task);

        if (count($errors) > 0) {
            $output = [];
            foreach ($errors as $error) {
                $output []= $error->getMessage().' property='.$error->getPropertyPath().' value='.$error->getInvalidValue();
                //echo '<pre>'.print_r($error, 1).'</pre>';
            }
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            //$errorsString = (string) $errors;

            return new Response(implode('<br />', $output));
        }

        return new Response('The author is valid! Yes!');
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

}