<?php

namespace App\Controller;

use App\Entity\SessionEvent;
use App\Entity\Tutoring;
use App\Entity\Article;
use App\Entity\User;
use App\Form\EnrollmentType;
use App\Form\ProfileType;
use App\Form\RegistrationFormType;
use App\Repository\ActivityRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleTypeRepository;
use App\Repository\ConferenceRepository;
use App\Repository\CourseRepository;
use App\Repository\PaymentRepository;
use App\Repository\TutoringRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;


class HomeController extends AbstractController
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
     * @Route("/", name="app_home")
     */
    public function index(ArticleRepository $articleRepository, ActivityRepository $activityRepository, ConferenceRepository $conferenceRepository, CourseRepository $courseRepository): Response
    {
        $articles = $articleRepository->findAll();
        $activities = $activityRepository->findAll();
        $conferences = $conferenceRepository->findAll();
        $courses = $courseRepository->findAll();

        return $this->render('home/home.html.twig', [
            'articles' => $articles,
            'activities' => $activities,
            'conferences' => $conferences,
            'courses' => $courses,
        ]);
    }

    /**
     * @Route("/profile", name="app_profile")
     */
    public function profile(UserRepository $userRepository): Response
    {
        $user =  $userRepository->find($this->getUser());

        return $this->render('home/user_profile.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile/edit", name="app_profile_edit", methods={"GET", "POST"})
     */
    public function editProfile(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user =  $userRepository->find($this->getUser());

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/user_profile_edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/whoarewe", name="app_whoarewe")
     */
    public function whoAreWe(): Response
    {
        return $this->render('home/whoarewe.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/volunteer", name="app_volunteer")
     */
    public function indexVolunteer(): Response
    {
        $user = $this->getUser();

        return $this->render('home/volunteer.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/tutoring", name="app_tutoring")
     */
    public function tutoring(): Response
    {
        $user = $this->getUser();

        return $this->render('home/tutoring.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/calendar", name="app_calendar", methods={"GET"})
     */
    public function calendar(): Response
    {
        return $this->render('home/calendar.html.twig');
    }

    /**
     * @Route("/myrequests", name="app_my_requests")
     */
    public function myRequests(UserRepository $userRepository): Response
    {
        $user =  $userRepository->find($this->getUser());

        $sessionsEvents = $user->getSessionEvent()->toArray();
        $payments = $user->getPayment()->toArray();
        $volunteer = $user->getVolunteer();
        $tutoring = $user->getTutoring();

//        dd($sessionsEvents);

        return $this->render('home/my_requests.html.twig', [
            'user' => $user,
            'sessionsEvents' => $sessionsEvents,
            'payments' => $payments,
            'volunteer' => $volunteer,
            'tutoring' => $tutoring
        ]);
    }

    /**
     * @Route("/events/", name="app_events")
     */
    public function events(ActivityRepository $activityRepository, ConferenceRepository $conferenceRepository, CourseRepository $courseRepository): Response
    {
        $activities = $activityRepository->findAll();
        $conferences = $conferenceRepository->findAll();
        $courses = $courseRepository->findAll();

        return $this->render('home/events.html.twig', [
            'activities' => $activities,
            'conferences' => $conferences,
            'courses' => $courses,
        ]);
    }

    /**
     * @Route("/events/info/{eventType}/{slug}", name="app_event_sessions", methods={"GET", "POST"})
     */
    public function eventSessions(Request $request, ActivityRepository $activityRepository, ConferenceRepository $conferenceRepository, CourseRepository $courseRepository, UserRepository $userRepository): Response
    {
        $session = $this->requestStack->getSession();
        $session->remove('slug');
        $session->remove('eventType');
        $session->remove('selectedSession');
        $session->remove('selectedEvent');

        $eventType = $request->attributes->get('eventType');
        $slug = $request->attributes->get('slug');

        $user =  $userRepository->find($this->getUser());

        if($eventType === 'activity'){
            $event = $activityRepository->findOneBy([
                'slug' => $slug
            ]);
        }
        elseif($eventType === 'conference'){
            $event = $conferenceRepository->findOneBy([
                'slug' => $slug
            ]);
        }
        else{
            $event = $courseRepository->findOneBy([
                'slug' => $slug
            ]);
        }

        return $this->render('home/event_sessions_list.html.twig', [
            'event' => $event,
            'eventType' => $eventType,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/articles", name="app_articles")
     */
    public function articles(ArticleRepository $articleRepository, ArticleTypeRepository $articleTypeRepository): Response
    {
        $articles = $articleRepository->findAll();
        $articlesType = $articleTypeRepository->findAll();

        return $this->render('home/articles.html.twig', [
            'articles' => $articles,
            'articlesType' => $articlesType,
        ]);
    }

    /**
     * @Route("/article/{id}", name="app_article_details", methods={"GET"})
     */
    public function articleDetails(Article $article): Response
    {
        return $this->render('home/article_details.html.twig', [
            'article' => $article,
        ]);
    }

}
