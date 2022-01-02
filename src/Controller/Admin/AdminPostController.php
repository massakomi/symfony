<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AdminBaseController;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepositoryInterface;
use App\Service\FileManagerServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AdminBaseController {

    private $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }


    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index()
    {
        $post = $this->getDoctrine()->getRepository(Post::class)
            ->findAll();
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
        $en = $this->getDoctrine()->getManager();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $this->postRepository->setCreatePost($post, $image);
            $this->addFlash('success', 'Пост добавлен');
            return $this->redirectToRoute('admin_post');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание поста';
        $forRender['posts'] = $post;
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig', $forRender);
    }
    
    /**
     * @Route("/admin/post/update/{id}", name="admin_post_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request, FileManagerServiceInterface $fileManagerService)
    {
        $post = $this->postRepository->getOnePost($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $image = $form->get('image')->getData();
                $this->postRepository->setUpdatePost($post, $image);
                $this->addFlash('success', 'Категория обновлена');
            }
            if ($form->get('delete')->isClicked()) {
                $this->postRepository->setDeletePost($post);
                $this->addFlash('success', 'Категория удалена');
            }
            return $this->redirectToRoute('admin_post');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Изменение категории';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig', $forRender);
    }
}