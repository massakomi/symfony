<?php


namespace App\Controller\Admin;


use Symfony\Bridge\Doctrine\PropertyInfo\DoctrineExtractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    protected $repository;
    protected $title;

    /**
     * Текущий класс
     */
    public function getClass(): string
    {
        $parts = explode("\\", static::class);
        $class = str_replace('Controller', '', array_pop($parts));
        return $class;
    }

    /**
     * Текущий класс в нижнем регистре
     */
    public function getAlias(): string
    {
        return mb_strtolower($this->getClass());
    }

    /**
     * Базовые параметры в шаблоне
     * @return array
     */
    public function renderDefault()
    {
        return [
            'breadcrumbs' => [],
            'alias' => $this->getAlias(),
            'title' => $this->title,
        ];
    }

    /**
     * Общий метод для отображения списка
     */
    public function listing()
    {
        $properties = $this->getClassProperties();
        $params = self::renderDefault();
        $params['properties'] = $properties;
        $params['data'] = $this->repository->findAll();
        return $this->render($this->getListingTemplate(), $params);
    }

    /**
     * Путь к шаблону для текущего общего списка
     */
    public function getListingTemplate()
    {
        $tplDir = $this->getParameter('kernel.project_dir').'/templates/';
        $listing = 'admin/'.$this->getAlias().'/index.html.twig';
        if (!file_exists($tplDir . $listing)) {
            $listing = 'admin/listing.html.twig';
        }
        return $listing;
    }

    /**
     * Все читаемые свойства текущего класса (для автогенерации таблицы данных
     */
    public function getClassProperties()
    {
        $class = 'App\Entity\\'.$this->getClass();
        $properties = [];
        $reflectionExtractor = new ReflectionExtractor();
        $props = $reflectionExtractor->getProperties($class);
        foreach ($props as $property) {
            if ($property == 'id') {
                continue;
            }
            if (!$reflectionExtractor->isReadable($class, $property)) {
                continue;
            }
            $types = $reflectionExtractor->getTypes($class, $property);
            $properties [$property] = false;
            if ($types) {
                foreach ($types as $type) {
                    $properties [$property] = $type->getBuiltinType();
                }
            }
        }
        return $properties;
    }

    /**
     * Общее действие по созданию или обновлению элементов
     */
    public function updateAction(int $id, Request $request)
    {
        $class = 'App\Entity\\'.$this->getClass();
        $classType = 'App\Form\\'.$this->getClass().'Type';
        if ($id) {
            $post = $this->repository->find($id);
        } else {
            $post = new $class();
        }
        $form = $this->createForm($classType, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                if (method_exists($this, 'save')) {
                    $this->save($id, $form, $post);
                } else {
                    $this->repository->manager->persist($post);
                    $this->repository->manager->flush();
                }
                $this->addFlash('success', ($id ? 'Изменено' : 'Добавлено'));
            }
            return $this->redirectToRoute('admin_'.$this->getAlias());
        }
        $params = self::renderDefault();
        $params['breadcrumbs'][$this->generateUrl('admin_'.$this->getAlias())]= $this->title;
        $params['breadcrumbs'][]= ($id ? 'Изменить' : 'Создать');
        $params['title'] = $this->title.': '.($id ? 'изменить' : 'создать');
        $params['form'] = $form->createView();
        return $this->render('admin/form.html.twig', $params);
    }

    /**
     * Общее действие по удалению
     */
    public function deleteAction($id)
    {
        $item = $this->repository->find($id);
        if (method_exists($this->repository, 'delete')) {
            $this->repository->delete($item);
        } else {
            $this->repository->manager->remove($item);
            $this->repository->manager->flush();
        }
        $this->addFlash('success', 'Удалено');
        return $this->redirectToRoute('admin_'.$this->getAlias());
    }

    /**
     * @Route("/admin", name="admin_home")
     * @return Response
     */
    public function adminHomePage()
    {
        $params = self::renderDefault();
        return $this->render('admin/index.html.twig', $params);
    }
}