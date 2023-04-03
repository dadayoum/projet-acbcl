<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\Tutoring;
use App\Entity\User;
use App\Entity\Volunteer;
use App\Form\EnrollmentType;
use App\Form\EnrollmentTutoringOtherMembersType;
use App\Repository\ActivityRepository;
use App\Repository\ConferenceRepository;
use App\Repository\CourseRepository;
use App\Repository\PaymentRepository;
use App\Repository\SessionEventRepository;
use App\Repository\TutoringRepository;
use App\Repository\UserRepository;
use App\Repository\VolunteerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/enrollment")
 */
class EnrollmentController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;

        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
    }

    /**
     * @Route("/volunteer", name="app_enrollment_volunteer", methods={"GET", "POST"})
     */
    public function newVolunteer(Request $request, UserRepository $userRepository, VolunteerRepository $volunteerRepository): Response
    {
        $volunteer = new Volunteer();
        $user = $this->getUser();
        $form = $this->createForm(EnrollmentType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $volunteer
                ->setUserVolunteer($user)
                ->setCreatedAt(new \DateTime())
                ->setIsValidated(false)
                ->setStatus('IN_PROGRESS');

            $userRepository->add($user, true);
            $volunteerRepository->add($volunteer, true);

            $this->addFlash(
                'volunteer',
                'Votre demande a été prise en compte'
            );

            return $this->redirectToRoute('app_volunteer', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enrollment/volunteer.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/tutoring", name="app_enrollment_tutoring", methods={"GET", "POST"})
     */
        public function newTutoring(Request $request, UserRepository $userRepository, TutoringRepository $tutoringRepository): Response
    {
        $tutoring = new Tutoring();
        $user = $this->getUser();

        $form = $this->createForm(EnrollmentType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tutoring
                ->setUserTutoring($user)
                ->setCreatedAt(new \DateTime())
                ->setStatus('IN_PROGRESS');

            $userRepository->add($user, true);
            $tutoringRepository->add($tutoring, true);

            $this->addFlash(
                'tutoring',
                'Votre demande a été prise en compte'
            );

            return $this->redirectToRoute('app_enrollment_tutoring_other_members', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enrollment/tutoring.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/tutoring/members", name="app_enrollment_tutoring_other_members", methods={"GET", "POST"})
     */
    public function newTutoringOtherMembers(Request $request, UserRepository $userRepository, TutoringRepository $tutoringRepository): Response
    {
        $user = $userRepository->find($this->getUser());
        $tutoring = $user->getTutoring();

        $form = $this->createForm(EnrollmentTutoringOtherMembersType::class, $tutoring);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tutoringRepository->add($tutoring, true);

            $this->addFlash(
                'notice',
                'Votre demande a été prise en compte'
            );

            return $this->redirectToRoute('app_tutoring', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('enrollment/tutoring_other_members.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/events/{eventType}/{slug}/{selectedSessionId}", name="app_enrollment_events_session", methods={"GET", "POST"})
     */
    public function eventsSession(Request $request, UserRepository $userRepository, SessionEventRepository $sessionEventRepository, PaymentRepository $paymentRepository): Response
    {
        $session = $this->requestStack->getSession();
        $session->remove('slug');
        $session->remove('eventType');
        $session->remove('selectedSession');
        $session->remove('selectedEvent');

        $sessionId = $request->attributes->get('selectedSessionId');
        $selectedSession = $sessionEventRepository->findOneBy([
            'id' => $sessionId,
        ]);

        $session->set('selectedSession', $selectedSession);
        $session->set('slug', $request->attributes->get('slug'));
        $session->set('eventType', $request->attributes->get('eventType'));

        $user =  $userRepository->find($this->getUser());

        $form = $this->createForm(EnrollmentType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository->add($user, true);

            if ($selectedSession->getPrice() == 0) {

                $user->addSessionEvent($selectedSession);
                $userRepository->add($user, true);

                $selectedSession->addUser($user);
                $sessionEventRepository->add($selectedSession, true);

                $payment = new Payment();
                $payment
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setPaymentType('FREE')
                    ->setPaid(true)
                    ->setSessionEvent($selectedSession)
                    ->setUserPayment($user);
                $paymentRepository->add($payment, true);

                $user->addPayment($payment);
                $userRepository->add($user, true);

                return $this->redirectToRoute('app_free_event_payment_success', [], Response::HTTP_SEE_OTHER);
            }
            else {
                return $this->redirectToRoute('app_payment', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('enrollment/session.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
