<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminProductController extends AdminBaseController
{

    private $repository;


    public function __construct(ProductRepository $productRepository)
    {
        $this->repository = $productRepository;
    }

    /**
     * @Route("/admin/product", name="admin_product")
     */
    public function index()
    {
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Товары';
        $forRender['data'] = $this->repository->findAll();
        $forRender['alias'] = 'product';
        return $this->render('admin/product/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/product/create", name="admin_product_create")
     * @param Request $request
     */
    public function create(Request $request, SluggerInterface $slugger)
    {
        return $this->update(0, $request, $slugger);
    }

    /**
     * @Route("/admin/product/update/{id}", name="admin_product_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request, SluggerInterface $slugger)
    {
        if ($id) {
            $product = $this->repository->find($id);
        } else {
            $product = new Product();
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $this->uploadFile($form, $slugger, $product);
                if ($id) {
                    $this->repository->setUpdate($product);
                    $this->addFlash('success', 'Обновлено');
                } else {
                    $this->repository->setCreate($product);
                    $this->addFlash('success', 'Добавлено');
                }
            }
            /*if ($form->get('delete')->isClicked()) {
                $this->repository->setDelete($product);
                $this->addFlash('success', 'Удалено');
            }*/
            return $this->redirectToRoute('admin_product');
        }
        $forRender = parent::renderDefault();
        $forRender['title'] = $id ? 'Изменение' : 'Создание';
        $forRender['form'] = $form->createView();
        if ($id) {
            if ($product->getImage()) {
                $forRender ['path'] = $this->getParameter('image_product_directory_http').$product->getImage();
                $product->setImage(
                    $this->getParameter('image_product_directory_http').$product->getImage()
                );
            }
        }
        return $this->render('admin/form.html.twig', $forRender);
    }

    /**
     * {@inheritdoc}
     */
    public function uploadFile($form, $slugger, $product)
    {
        $image = $form->get('imagefile')->getData();
        if ($image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

            try {
                $image->move(
                    $this->getParameter('image_product_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $product->setImage($newFilename);
        }
    }

    /**
     * @Route("/admin/product/delete/{id}", name="admin_product_delete")
     * @param Request $request
     */
    public function admin_product_delete(int $id, Request $request)
    {
        $product = $this->repository->find($id);
        $this->repository->setDelete($product);
        $this->addFlash('success', 'Удалено');
        return $this->redirectToRoute('admin_product');
    }
}