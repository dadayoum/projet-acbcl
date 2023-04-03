<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentType;
use App\Repository\PaymentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/payment")
 */
class AdminPaymentController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_payment_index", methods={"GET"})
     */
    public function index(PaymentRepository $paymentRepository): Response
    {
        return $this->render('admin_payment/index.html.twig', [
            'payments' => $paymentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_payment_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PaymentRepository $paymentRepository): Response
    {
        $payment = new Payment();
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payment->setCreatedAt(new \DateTimeImmutable());
            $paymentRepository->add($payment, true);

            return $this->redirectToRoute('app_admin_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_payment_show", methods={"GET"})
     */
    public function show(Payment $payment): Response
    {
        return $this->render('admin_payment/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_payment_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Payment $payment, PaymentRepository $paymentRepository): Response
    {
        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payment->setUpdatedAt(new \DateTimeImmutable());
            $paymentRepository->add($payment, true);

            return $this->redirectToRoute('app_admin_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_payment/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_payment_delete", methods={"POST"})
     */
    public function delete(Request $request, Payment $payment, PaymentRepository $paymentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->request->get('_token'))) {
            $paymentRepository->remove($payment, true);
        }

        return $this->redirectToRoute('app_admin_payment_index', [], Response::HTTP_SEE_OTHER);
    }
}
