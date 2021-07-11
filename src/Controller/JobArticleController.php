<?php

namespace App\Controller;

use App\Entity\JobArticle;
use App\Form\JobArticleType;
use App\Repository\JobArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/job/article')]
class JobArticleController extends AbstractController
{
    #[Route('/', name: 'job_article_index', methods: ['GET'])]
    public function index(JobArticleRepository $jobArticleRepository): Response
    {
        return $this->render('job_article/index.html.twig', [
            'job_articles' => $jobArticleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'job_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $jobArticle = new JobArticle();
        $form = $this->createForm(JobArticleType::class, $jobArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jobArticle);
            $entityManager->flush();

            return $this->redirectToRoute('job_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('job_article/new.html.twig', [
            'job_article' => $jobArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'job_article_show', methods: ['GET'])]
    public function show(JobArticle $jobArticle): Response
    {
        return $this->render('job_article/show.html.twig', [
            'job_article' => $jobArticle,
        ]);
    }

    #[Route('/{id}/edit', name: 'job_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JobArticle $jobArticle): Response
    {
        $form = $this->createForm(JobArticleType::class, $jobArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('job_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('job_article/edit.html.twig', [
            'job_article' => $jobArticle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'job_article_delete', methods: ['POST'])]
    public function delete(Request $request, JobArticle $jobArticle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jobArticle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($jobArticle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('job_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
