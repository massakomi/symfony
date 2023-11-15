<?php

namespace App\Controller\Admin;

use App\Repository\PaymentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends BaseController
{
    protected $repository;

    protected $title = 'Способы оплаты';

    public function __construct(PaymentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/admin/payment", name="admin_payment")
     */
    public function index()
    {
        return $this->listing();
    }

    /**
     * @Route("/admin/payment/create", name="admin_payment_create")
     */
    public function create(Request $request)
    {
        return $this->updateAction(0, $request);
    }

    /**
     * @Route("/admin/payment/update/{id}", name="admin_payment_update")
     * @param int $id
     * @param Request $request
     */
    public function update(int $id, Request $request)
    {
        return $this->updateAction($id, $request);
    }

    /**
     * @Route("/admin/payment/delete/{id}", name="admin_payment_delete")
     * @param Request $request
     */
    public function admin_payment_delete(int $id)
    {
        return $this->deleteAction($id);
    }

}