<?php

namespace App\Controller\Admin;

use App\Repository\OrderStatusRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderStatusController extends BaseController
{
    protected $repository;

    protected $title = 'Статусы заказов';

    public function __construct(OrderStatusRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/orderstatus", name="admin_orderstatus")
     */
    public function index()
    {
        return $this->listing();
    }

    /**
     * @Route("/admin/orderstatus/create", name="admin_orderstatus_create")
     */
    public function create(Request $request)
    {
        return $this->updateAction(0, $request);
    }

    /**
     * @Route("/admin/orderstatus/update/{id}", name="admin_orderstatus_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request)
    {
        return $this->updateAction($id, $request);
    }

    /**
     * @Route("/admin/orderstatus/delete/{id}", name="admin_orderstatus_delete")
     * @param Request $request
     */
    public function admin_orderstatus_delete(int $id)
    {
        return $this->deleteAction($id);
    }

}