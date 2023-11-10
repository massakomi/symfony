<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBaseController extends AbstractController
{
    public function renderDefault()
    {
        return [
            'title' => 'Админочка '
        ];
    }

    /**
     * @Route("/admin", name="admin_home")
     * @return Response
     */
    public function index()
    {
        $forRender = self::renderDefault();
        return $this->render('admin/index.html.twig', $forRender);
    }
}