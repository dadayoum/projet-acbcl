<?php

namespace App\Controller;

use App\Entity\SpecialEmails;
use App\Form\SpecialEmailsType;
use App\Repository\SpecialEmailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/special_emails")
 */
class AdminSpecialEmailsController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_special_emails_index", methods={"GET"})
     */
    public function index(SpecialEmailsRepository $specialEmailsRepository): Response
    {
        return $this->render('admin_special_emails/index.html.twig', [
            'special_emails' => $specialEmailsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_special_emails_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SpecialEmailsRepository $specialEmailsRepository): Response
    {
        $specialEmail = new SpecialEmails();
        $form = $this->createForm(SpecialEmailsType::class, $specialEmail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $specialEmailsRepository->add($specialEmail, true);

            return $this->redirectToRoute('app_admin_special_emails_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_special_emails/new.html.twig', [
            'special_email' => $specialEmail,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_special_emails_show", methods={"GET"})
     */
    public function show(SpecialEmails $specialEmail): Response
    {
        return $this->render('admin_special_emails/show.html.twig', [
            'special_email' => $specialEmail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_special_emails_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SpecialEmails $specialEmail, SpecialEmailsRepository $specialEmailsRepository): Response
    {
        $form = $this->createForm(SpecialEmailsType::class, $specialEmail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $specialEmailsRepository->add($specialEmail, true);

            return $this->redirectToRoute('app_admin_special_emails_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_special_emails/edit.html.twig', [
            'special_email' => $specialEmail,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_special_emails_delete", methods={"POST"})
     */
    public function delete(Request $request, SpecialEmails $specialEmail, SpecialEmailsRepository $specialEmailsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$specialEmail->getId(), $request->request->get('_token'))) {
            $specialEmailsRepository->remove($specialEmail, true);
        }

        return $this->redirectToRoute('app_admin_special_emails_index', [], Response::HTTP_SEE_OTHER);
    }
}
