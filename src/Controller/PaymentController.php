<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\SessionEvent;
use App\Repository\ActivityRepository;
use App\Repository\ConferenceRepository;
use App\Repository\CourseRepository;
use App\Repository\PaymentRepository;
use App\Repository\SessionEventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;


class PaymentController extends AbstractController
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
     * @Route("/payment", name="app_payment")
     */
    public function index(Request $request, UserRepository $userRepository, ActivityRepository $activityRepository, ConferenceRepository $conferenceRepository, CourseRepository $courseRepository): Response
    {
        $session = $this->requestStack->getSession();

        $user = $userRepository->find($this->getUser());
        $selectedSession = $this->get('session')->get('selectedSession');
        $eventType = $this->get('session')->get('eventType');
        $slug = $this->get('session')->get('slug');

        if($eventType === 'activity'){
            $selectedEvent = $activityRepository->findOneBy([
                'id' => $selectedSession->getActivity()->getId(),
            ]);
        }
        elseif($eventType === 'conference'){
            $selectedEvent = $conferenceRepository->findOneBy([
                'id' => $selectedSession->getConference()->getId(),
            ]);
        }
        else{
            $selectedEvent = $courseRepository->findOneBy([
                'id' => $selectedSession->getCourse()->getId(),
            ]);
        }

        $session->set('selectedEvent', $selectedEvent);

        return $this->render('payment/payment.html.twig', [
            'eventType' => $eventType,
            'slug' => $slug,
            'sessionEvent' => $selectedSession,
            'name' => $selectedEvent->getName(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/payment/checkout", name="app_checkout")
     */
    public function checkout(Request $request, UserRepository $userRepository): Response
    {
        if($_ENV['APP_ENV'] === 'dev') {
            $secretKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        }
        else {
            $secretKey = $_ENV['STRIPE_SECRET_KEY_LIVE'];
        }

        $user =  $userRepository->find($this->getUser());
        $eventType = $this->get('session')->get('eventType');
        $selectedSession = $this->get('session')->get('selectedSession');
        $selectedEvent = $this->get('session')->get('selectedEvent');


        // This is your test secret API key.
        $stripe = new \Stripe\StripeClient($secretKey);

        $checkout_session = $stripe->checkout->sessions->create([
            'customer_email' => $user->getEmail(),
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $selectedEvent->getName(),
                    ],
                    'unit_amount' => $selectedSession->getPrice() * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->generateUrl('app_payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($checkout_session->url, 303);
    }

    /**
     * @Route("/payment/success", name="app_payment_success", methods={"GET", "POST"})
     */
    public function paymentSuccess(Request $request, UserRepository $userRepository, SessionEventRepository $sessionEventRepository, PaymentRepository $paymentRepository): Response
    {
        $user =  $userRepository->find($this->getUser());
        $selectedSessionId = $this->get('session')->get('selectedSession')->getId();
        $sessionEvent = $sessionEventRepository->findOneBy([
            'id' => $selectedSessionId
        ]);

        if($request->query->get('session_id'))
        {
            $user->addSessionEvent($sessionEvent);
            $userRepository->add($user, true);

            $sessionEvent->addUser($user);
            $sessionEventRepository->add($sessionEvent, true);

            $payment = new Payment();
            $payment
                ->setCreatedAt(new \DateTimeImmutable())
                ->setPaid(true)
                ->setPaymentType('STRIPE')
                ->setSessionEvent($sessionEvent)
                ->setUserPayment($user);
            $paymentRepository->add($payment, true);

            $user->addPayment($payment);
            $userRepository->add($user, true);
        }

        return $this->render('payment/payment_success.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/payment/cancel", name="app_payment_cancel")
     */
    public function paymentCancel(Request $request, UserRepository $userRepository): Response
    {
        return $this->render('payment/payment_cancel.html.twig', [

        ]);
    }

    /**
     * @Route("/payment/freeEvent/success", name="app_free_event_payment_success")
     */
    public function freeEventPaymentSuccess(Request $request, UserRepository $userRepository): Response
    {
        return $this->render('payment/free_event_payment_success.html.twig', [

        ]);
    }

    /**
     * @Route("/payment/wait/success", name="app_success_without_payment", methods={"GET", "POST"})
     */
    public function successWithoutPayment(Request $request, UserRepository $userRepository, SessionEventRepository $sessionEventRepository, PaymentRepository $paymentRepository): Response
    {
        $user =  $userRepository->find($this->getUser());
        $selectedSessionId = $this->get('session')->get('selectedSession')->getId();
        $sessionEvent = $sessionEventRepository->findOneBy([
            'id' => $selectedSessionId
        ]);

        $user->addSessionEvent($sessionEvent);
        $userRepository->add($user, true);

        $sessionEvent->addUser($user);
        $sessionEventRepository->add($sessionEvent, true);

        $payment = new Payment();
        $payment
            ->setCreatedAt(new \DateTimeImmutable())
            ->setPaid(false)
            ->setPaymentType('UNDEFINED')
            ->setSessionEvent($sessionEvent)
            ->setUserPayment($user);
        $paymentRepository->add($payment, true);

        $user->addPayment($payment);
        $userRepository->add($user, true);


        return $this->render('payment/payment_wait_success.html.twig', [
            'user' => $user,
        ]);
    }
}
