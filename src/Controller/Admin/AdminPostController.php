<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminBaseController;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\FileManagerServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AdminBaseController {

    private $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index()
    {
        $post = $this->repository->findAll();
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Посты';
        $forRender['posts'] = $post;
        return $this->render('admin/post/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/post/create", name="admin_post_create")
     */
    public function create(Request $request)
    {
        return $this->update(0, $request);
    }
    
    /**
     * @Route("/admin/post/update/{id}", name="admin_post_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request)
    {
        if ($id) {
            $post = $this->repository->getOnePost($id);
        } else {
            $post = new Post();
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $image = $form->get('image')->getData();
                if ($id) {
                    $this->repository->setUpdatePost($post, $image);
                } else {
                    $this->repository->setCreatePost($post, $image);
                }
                $this->addFlash('success', 'Публикация '.($id ? 'изменена' : 'добавлена'));
            }
            return $this->redirectToRoute('admin_post');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = ($id ? 'Изменение' : 'Создание').' публикации';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig', $forRender);
    }

    /**
     * @Route("/admin/post/delete/{id}", name="admin_post_delete")
     * @param Request $request
     */
    public function admin_post_delete(int $id, Request $request)
    {
        $product = $this->repository->find($id);
        $this->repository->setDeletePost($product);
        $this->addFlash('success', 'Удалено');
        return $this->redirectToRoute('admin_product');
    }
}