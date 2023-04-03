<?php

namespace App\Controller;

use App\Entity\Volunteer;
use App\Form\VolunteerType;
use App\Form\VolunteerEditType;
use App\Repository\VolunteerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/volunteer")
 */
class AdminVolunteerController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_volunteer_index", methods={"GET"})
     */
    public function index(VolunteerRepository $volunteerRepository): Response
    {
        return $this->render('admin_volunteer/index.html.twig', [
            'volunteers' => $volunteerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_volunteer_new", methods={"GET", "POST"})
     */
    public function new(Request $request, VolunteerRepository $volunteerRepository): Response
    {
        $volunteer = new Volunteer();
        $form = $this->createForm(VolunteerType::class, $volunteer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $volunteer
                ->setCreatedAt(new \DateTime());
            $volunteerRepository->add($volunteer, true);

            return $this->redirectToRoute('app_admin_volunteer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_volunteer/new.html.twig', [
            'volunteer' => $volunteer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_volunteer_show", methods={"GET"})
     */
    public function show(Volunteer $volunteer): Response
    {
        return $this->render('admin_volunteer/show.html.twig', [
            'volunteer' => $volunteer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_volunteer_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Volunteer $volunteer, VolunteerRepository $volunteerRepository): Response
    {
        $form = $this->createForm(VolunteerEditType::class, $volunteer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $volunteer->setUpdatedAt(new \DateTime());
            $volunteerRepository->add($volunteer, true);

            return $this->redirectToRoute('app_admin_volunteer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_volunteer/edit.html.twig', [
            'volunteer' => $volunteer,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_volunteer_delete", methods={"POST"})
     */
    public function delete(Request $request, Volunteer $volunteer, VolunteerRepository $volunteerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$volunteer->getId(), $request->request->get('_token'))) {
            $volunteer->getUserVolunteer()->setVolunteer(null);
            $volunteerRepository->remove($volunteer, true);
        }

        return $this->redirectToRoute('app_admin_volunteer_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/approved", name="app_admin_volunteer_approved", methods={"GET", "POST"})
     */
    public function approved(Request $request, Volunteer $volunteer, VolunteerRepository $volunteerRepository): Response
    {
        $volunteer
            ->setUpdatedAt(new \DateTime())
            ->setIsValidated(true)
            ->setStatus('COMPLETE');
        $volunteerRepository->add($volunteer, true);

        return $this->redirectToRoute('app_admin_volunteer_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/rejected", name="app_admin_volunteer_rejected", methods={"GET", "POST"})
     */
    public function rejected(Request $request, Volunteer $volunteer, VolunteerRepository $volunteerRepository): Response
    {
        $volunteer
            ->setUpdatedAt(new \DateTime())
            ->setIsValidated(false)
            ->setStatus('COMPLETE');
        $volunteerRepository->add($volunteer, true);

        return $this->redirectToRoute('app_admin_volunteer_index', [], Response::HTTP_SEE_OTHER);
    }
}
