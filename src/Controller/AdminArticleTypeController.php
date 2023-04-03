<?php

namespace App\Controller;

use App\Entity\ArticleType;
use App\Form\ArticleTypeType;
use App\Repository\ArticleTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/article_type")
 */
class AdminArticleTypeController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_article_type_index", methods={"GET"})
     */
    public function index(ArticleTypeRepository $articleTypeRepository): Response
    {
        return $this->render('admin_article_type/index.html.twig', [
            'article_types' => $articleTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_article_type_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ArticleTypeRepository $articleTypeRepository): Response
    {
        $articleType = new ArticleType();
        $form = $this->createForm(ArticleTypeType::class, $articleType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleTypeRepository->add($articleType, true);

            return $this->redirectToRoute('app_admin_article_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_article_type/new.html.twig', [
            'article_type' => $articleType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_article_type_show", methods={"GET"})
     */
    public function show(ArticleType $articleType): Response
    {
        return $this->render('admin_article_type/show.html.twig', [
            'article_type' => $articleType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_article_type_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ArticleType $articleType, ArticleTypeRepository $articleTypeRepository): Response
    {
        $form = $this->createForm(ArticleTypeType::class, $articleType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleTypeRepository->add($articleType, true);

            return $this->redirectToRoute('app_admin_article_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_article_type/edit.html.twig', [
            'article_type' => $articleType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_article_type_delete", methods={"POST"})
     */
    public function delete(Request $request, ArticleType $articleType, ArticleTypeRepository $articleTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$articleType->getId(), $request->request->get('_token'))) {
            $articleTypeRepository->remove($articleType, true);
        }

        return $this->redirectToRoute('app_admin_article_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
