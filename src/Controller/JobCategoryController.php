<?php

namespace App\Controller;

use App\Entity\JobCategory;
use App\Form\JobCategoryType;
use App\Repository\JobCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/job/category')]
class JobCategoryController extends AbstractController
{
    #[Route('/', name: 'job_category_index', methods: ['GET'])]
    public function index(JobCategoryRepository $jobCategoryRepository): Response
    {
        return $this->render('job_category/index.html.twig', [
            'job_categories' => $jobCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'job_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $jobCategory = new JobCategory();
        $form = $this->createForm(JobCategoryType::class, $jobCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($jobCategory);
            $entityManager->flush();

            return $this->redirectToRoute('job_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('job_category/new.html.twig', [
            'job_category' => $jobCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'job_category_show', methods: ['GET'])]
    public function show(JobCategory $jobCategory): Response
    {
        return $this->render('job_category/show.html.twig', [
            'job_category' => $jobCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'job_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JobCategory $jobCategory): Response
    {
        $form = $this->createForm(JobCategoryType::class, $jobCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('job_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('job_category/edit.html.twig', [
            'job_category' => $jobCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'job_category_delete', methods: ['POST'])]
    public function delete(Request $request, JobCategory $jobCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jobCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($jobCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('job_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
