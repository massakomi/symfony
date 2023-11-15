<?php

namespace App\Controller\Admin;

use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends BaseController
{
    protected $repository;

    protected $title = 'Заказы';

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/order", name="admin_order")
     */
    public function index()
    {
        return $this->listing();
    }

    /**
     * @Route("/admin/order/create", name="admin_order_create")
     */
    public function create(Request $request)
    {
        return $this->updateAction(0, $request);
    }

    /**
     * @Route("/admin/order/update/{id}", name="admin_order_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request)
    {
        return $this->updateAction($id, $request);
    }

    /**
     * @Route("/admin/order/delete/{id}", name="admin_order_delete")
     * @param Request $request
     */
    public function admin_order_delete(int $id)
    {
        return $this->deleteAction($id);
    }

}