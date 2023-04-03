<?php

namespace App\Controller;

use App\Entity\SessionEvent;
use App\Entity\User;
use App\Form\SessionEventType;
use App\Form\SessionEventAddUserType;
use App\Form\SessionEventRemoveUserType;
use App\Repository\SessionEventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Translation\t;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/admin/session")
 */
class AdminSessionController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_session_index", methods={"GET"})
     */
    public function index(SessionEventRepository $sessionEventRepository): Response
    {
        return $this->render('admin_session/index.html.twig', [
            'sessionsEvent' => $sessionEventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_session_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SessionEventRepository $sessionEventRepository): Response
    {
        $sessionEvent = new SessionEvent();
        $form = $this->createForm(SessionEventType::class, $sessionEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sessionEvent->setCreatedAt(new \DateTimeImmutable());
            $sessionEventRepository->add($sessionEvent, true);

            return $this->redirectToRoute('app_admin_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_session/new.html.twig', [
            'sessionEvent' => $sessionEvent,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_session_show", methods={"GET"})
     */
    public function show(SessionEvent $sessionEvent, UserRepository $userRepository, SessionEventRepository $sessionEventRepository): Response
    {
        return $this->render('admin_session/show.html.twig', [
            'sessionEvent' => $sessionEvent,
            'registrants' => $sessionEvent->getUsers(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_session_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SessionEvent $sessionEvent, SessionEventRepository $sessionEventRepository): Response
    {
        $form = $this->createForm(SessionEventType::class, $sessionEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sessionEvent->setUpdatedAt(new \DateTimeImmutable());
            $sessionEventRepository->add($sessionEvent, true);

            return $this->redirectToRoute('app_admin_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_session/edit.html.twig', [
            'sessionEvent' => $sessionEvent,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_session_delete", methods={"POST"})
     */
    public function delete(Request $request, SessionEvent $sessionEvent, SessionEventRepository $sessionEventRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sessionEvent->getId(), $request->request->get('_token'))) {
            $sessionEventRepository->remove($sessionEvent, true);
        }

        return $this->redirectToRoute('app_admin_session_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/add/user", name="app_admin_session_add_user", methods={"GET", "POST"})
     */
    public function addUser(Request $request, SessionEvent $sessionEvent, SessionEventRepository $sessionEventRepository, UserRepository $userRepository): Response
    {
        $form = $this->createForm(SessionEventAddUserType::class, $sessionEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach($request->request->get('session_event_add_user')['users'] as $findUsers){
                $findUser = $userRepository->find($findUsers);

                $sessionEvent->addUser($findUser);

                $sessionEventRepository->add($sessionEvent, true);
            }

            return $this->redirectToRoute('app_admin_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_session/add.html.twig', [
            'sessionEvent' => $sessionEvent,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/remove/user", name="app_admin_session_remove_user", methods={"GET", "POST"})
     */
    public function removeUser(Request $request, SessionEvent $sessionEvent, SessionEventRepository $sessionEventRepository, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(SessionEventRemoveUserType::class, $sessionEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($request->request->get('session_event_remove_user')['users'] as $findUsers){
                $findUser = $userRepository->find($findUsers);

                $sessionEvent->removeUser($findUser);

                $sessionEventRepository->add($sessionEvent, true);
            }

            return $this->redirectToRoute('app_admin_session_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_session/remove.html.twig', [
            'sessionEvent' => $sessionEvent,
            'form' => $form,
        ]);
    }
}
