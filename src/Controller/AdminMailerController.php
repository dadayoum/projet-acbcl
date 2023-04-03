<?php

namespace App\Controller;

use App\Form\AdminMailerFormType;
use App\Form\AdminMailerVolunteerGroupFormType;
use App\Form\AdminMailerTutoringGroupFormType;
use App\Form\AdminMailerAllFormType;
use App\Repository\SpecialEmailsRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class AdminMailerController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/admin/mailer", name="app_admin_mailer")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(AdminMailerFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $recipients = [];
//            $ccRecipents = [];
            $subject = $formData['subject'];
            $content = $formData['content'];

            foreach ($formData['users'] as $recipient){
                $recipients[] = $recipient->getEmail();
            }

//            foreach ($formData['cc_users'] as $ccRecipent){
//                $ccRecipents[] = $ccRecipent->getEmail();
//            }

            $message = (new TemplatedEmail())
                    ->from(new Address('acbcl@gmail.com', 'ACBCL Mail Bot'))
                    ->to(...$recipients)
//                    ->addCc(...$ccRecipents)
                    ->subject($subject)
                    ->htmlTemplate('admin_mailer/email.html.twig')
                    ->context([
                        'content' => $content
                    ])
//                    ->text($formData['message'])
            ;
            $mailer->send($message);

            $this->addFlash('success', 'Votre message a été envoyé');

            return $this->redirectToRoute('app_admin');
        }


        return $this->render('admin_mailer/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/mailerVolunteerGroup", name="app_admin_mailer_volunteer_group")
     */
    public function volunteerGroup(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(AdminMailerVolunteerGroupFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $recipients = [];
//            $ccRecipents = [];
            $subject = $formData['subject'];
            $content = $formData['content'];

            foreach ($formData['users'] as $recipient){
                $recipients[] = $recipient->getEmail();
            }

//            foreach ($formData['cc_users'] as $ccRecipent){
//                $ccRecipents[] = $ccRecipent->getEmail();
//            }

            $message = (new TemplatedEmail())
                ->from(new Address('acbcl@gmail.com', 'ACBCL Mail Bot'))
                ->to(...$recipients)
//                    ->addCc(...$ccRecipents)
                ->subject($subject)
                ->htmlTemplate('admin_mailer/email.html.twig')
                ->context([
                    'content' => $content
                ])
//                    ->text($formData['message'])
            ;
            $mailer->send($message);

            $this->addFlash('success', 'Votre message a été envoyé');

            return $this->redirectToRoute('app_admin');
        }


        return $this->render('admin_mailer/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/mailerTutoringGroup", name="app_admin_mailer_tutoring_group")
     */
    public function tutoringGroup(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(AdminMailerTutoringGroupFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $recipients = [];
//            $ccRecipents = [];
            $subject = $formData['subject'];
            $content = $formData['content'];

            foreach ($formData['users'] as $recipient){
                $recipients[] = $recipient->getEmail();
            }

//            foreach ($formData['cc_users'] as $ccRecipent){
//                $ccRecipents[] = $ccRecipent->getEmail();
//            }

            $message = (new TemplatedEmail())
                ->from(new Address('acbcl@gmail.com', 'ACBCL Mail Bot'))
                ->to(...$recipients)
//                    ->addCc(...$ccRecipents)
                ->subject($subject)
                ->htmlTemplate('admin_mailer/email.html.twig')
                ->context([
                    'content' => $content
                ])
//                    ->text($formData['message'])
            ;
            $mailer->send($message);

            $this->addFlash('success', 'Votre message a été envoyé');

            return $this->redirectToRoute('app_admin');
        }


        return $this->render('admin_mailer/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/mailerAll", name="app_admin_mailer_all")
     */
    public function all(Request $request, MailerInterface $mailer, UserRepository $userRepository, SpecialEmailsRepository $specialEmailsRepository): Response
    {
        $form = $this->createForm(AdminMailerAllFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            $recipients = [];
//            $ccRecipents = [];
            $subject = $formData['subject'];
            $content = $formData['content'];

            $usersSub = $userRepository->findAll();
            $usersSpe = $specialEmailsRepository->findAll();

            foreach ($usersSub as $uSub){
                $recipients[] = $uSub->getEmail();
            }

            foreach ($usersSpe as $uSpe){
                $recipients[] = $uSpe->getEmail();
            }

            $message = (new TemplatedEmail())
                ->from(new Address('acbcl@gmail.com', 'ACBCL Mail Bot'))
                ->to(...$recipients)
//                    ->addCc(...$ccRecipents)
                ->subject($subject)
                ->htmlTemplate('admin_mailer/email.html.twig')
                ->context([
                    'content' => $content
                ])
//                    ->text($formData['message'])
            ;
            $mailer->send($message);

            $this->addFlash('success', 'Votre message a été envoyé');

            return $this->redirectToRoute('app_admin');
        }


        return $this->render('admin_mailer/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
