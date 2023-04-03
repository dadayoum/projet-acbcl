<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleEditType;
use App\Repository\ArticleRepository;
use App\Repository\SpecialEmailsRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/article")
 */
class AdminArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('admin_article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_article_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ArticleRepository $articleRepository, MailerInterface $mailer, UserRepository $userRepository, SpecialEmailsRepository $specialEmailsRepository): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleEditType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTimeImmutable());
            $articleRepository->add($article, true);

            $recipients = [];
            $subject = 'Un nouvel article a été publié sur ACBCL !';
            $content = 'Venez lire notre nouveau contenu sur www.acbcl.net';

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

            return $this->redirectToRoute('app_admin_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('admin_article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_article_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        $form = $this->createForm(ArticleEditType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new \DateTimeImmutable());
            $articleRepository->add($article, true);

            return $this->redirectToRoute('app_admin_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_article_delete", methods={"POST"})
     */
    public function delete(Request $request, Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $articleRepository->remove($article, true);
        }

        return $this->redirectToRoute('app_admin_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
