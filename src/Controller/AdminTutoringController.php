<?php

namespace App\Controller;

use App\Entity\Tutoring;
use App\Form\TutoringType;
use App\Form\TutoringEditType;
use App\Repository\TutoringRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tutoring")
 */
class AdminTutoringController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_tutoring_index", methods={"GET"})
     */
    public function index(TutoringRepository $tutoringRepository): Response
    {
        return $this->render('admin_tutoring/index.html.twig', [
            'tutorings' => $tutoringRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_tutoring_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TutoringRepository $tutoringRepository): Response
    {
        $tutoring = new Tutoring();
        $form = $this->createForm(TutoringType::class, $tutoring);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tutoring
                ->setCreatedAt(new \DateTime());
            $tutoringRepository->add($tutoring, true);

            return $this->redirectToRoute('app_admin_tutoring_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_tutoring/new.html.twig', [
            'tutoring' => $tutoring,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_tutoring_show", methods={"GET"})
     */
    public function show(Tutoring $tutoring): Response
    {
        return $this->render('admin_tutoring/show.html.twig', [
            'tutoring' => $tutoring,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_tutoring_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tutoring $tutoring, TutoringRepository $tutoringRepository): Response
    {
        $form = $this->createForm(TutoringEditType::class, $tutoring);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tutoring->setUpdatedAt(new \DateTime());
            $tutoringRepository->add($tutoring, true);

            return $this->redirectToRoute('app_admin_tutoring_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_tutoring/edit.html.twig', [
            'tutoring' => $tutoring,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_tutoring_delete", methods={"POST"})
     */
    public function delete(Request $request, Tutoring $tutoring, TutoringRepository $tutoringRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tutoring->getId(), $request->request->get('_token'))) {
            $tutoring->getUserTutoring()->setTutoring(null);
            $tutoringRepository->remove($tutoring, true);
        }

        return $this->redirectToRoute('app_admin_tutoring_index', [], Response::HTTP_SEE_OTHER);
    }
}
