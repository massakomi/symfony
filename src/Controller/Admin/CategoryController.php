<?php

namespace App\Controller\Admin;

use App\Controller\Admin\BaseController;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends BaseController
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/category", name="admin_category")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Категории';
        $forRender['data'] = $this->repository->getAllCategory();
        $forRender['alias'] = 'category';
        return $this->render('admin/category/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/category/create", name="admin_category_create")
     * @param Request $request
     */
    public function create(Request $request)
    {
        return $this->update(0, $request);
    }


    /**
     * @Route("/admin/category/update/{id}", name="admin_category_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request)
    {
        if ($id) {
            $category = $this->repository->getOneCategory($id);
        } else {
            $category = new Category();
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                if ($id) {
                    $this->repository->setUpdateCategory($category);
                } else {
                    $this->repository->setCreateCategory($category);
                }
                $this->addFlash('success', 'Категория '.($id ? 'изменена' : 'добавлена'));
            }
            return $this->redirectToRoute('admin_category');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = $id ? 'Изменение' : 'Создание';
        $forRender['form'] = $form->createView();
        return $this->render('admin/form.html.twig', $forRender);
    }

    /**
     * @Route("/admin/category/delete/{id}", name="admin_category_delete")
     * @param Request $request
     */
    public function admin_category_delete(int $id, Request $request)
    {
        $product = $this->repository->find($id);
        $this->repository->setDelete($product);
        $this->addFlash('success', 'Удалено');
        return $this->redirectToRoute('admin_product');
    }
}