<?php

namespace App\Controller\Admin;

use App\Repository\ShipmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShipmentController extends BaseController
{
    protected $repository;

    protected $title = 'Способы доставки';

    public function __construct(ShipmentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/shipment", name="admin_shipment")
     */
    public function index()
    {
        return $this->listing();
    }

    /**
     * @Route("/admin/shipment/create", name="admin_shipment_create")
     */
    public function create(Request $request)
    {
        return $this->updateAction(0, $request);
    }

    /**
     * @Route("/admin/shipment/update/{id}", name="admin_shipment_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request)
    {
        return $this->updateAction($id, $request);
    }

    /**
     * @Route("/admin/shipment/delete/{id}", name="admin_shipment_delete")
     * @param Request $request
     */
    public function admin_shipment_delete(int $id)
    {
        return $this->deleteAction($id);
    }

}