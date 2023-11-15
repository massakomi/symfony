<?php

namespace App\Controller\Admin;

use App\Controller\Admin\BaseController;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\FileManagerServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends BaseController {

    protected $repository;

    protected $title = 'Посты';

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index()
    {
        return $this->listing();
    }

    /**
     * @Route("/admin/post/create", name="admin_post_create")
     */
    public function create(Request $request)
    {
        return $this->updateAction(0, $request);
    }
    
    /**
     * @Route("/admin/post/update/{id}", name="admin_post_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request)
    {
        return $this->updateAction($id, $request);
    }

    /**
     * @Route("/admin/post/delete/{id}", name="admin_post_delete")
     * @param Request $request
     */
    public function admin_post_delete(int $id)
    {
        return $this->deleteAction($id);
    }

    /**
     * {@inheritdoc}
     */
    public function save($id, $form, $post)
    {
        $image = $form->get('image')->getData();
        if ($id) {
            $this->repository->setUpdatePost($post, $image);
        } else {
            $this->repository->setCreatePost($post, $image);
        }
    }
}