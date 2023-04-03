<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/conference")
 */
class AdminConferenceController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_conference_index", methods={"GET"})
     */
    public function index(ConferenceRepository $conferenceRepository): Response
    {
        return $this->render('admin_conference/index.html.twig', [
            'conferences' => $conferenceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_conference_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ConferenceRepository $conferenceRepository): Response
    {
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conferenceRepository->add($conference, true);

            return $this->redirectToRoute('app_admin_conference_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_conference/new.html.twig', [
            'conference' => $conference,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_conference_show", methods={"GET"})
     */
    public function show(Conference $conference): Response
    {
        return $this->render('admin_conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_conference_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Conference $conference, ConferenceRepository $conferenceRepository): Response
    {
        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conferenceRepository->add($conference, true);

            return $this->redirectToRoute('app_admin_conference_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_conference/edit.html.twig', [
            'conference' => $conference,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_conference_delete", methods={"POST"})
     */
    public function delete(Request $request, Conference $conference, ConferenceRepository $conferenceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conference->getId(), $request->request->get('_token'))) {
            $conferenceRepository->remove($conference, true);
        }

        return $this->redirectToRoute('app_admin_conference_index', [], Response::HTTP_SEE_OTHER);
    }
}
