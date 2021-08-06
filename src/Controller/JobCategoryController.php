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
            'job_categories' => $jobCategoryRepository->findBy(['parent' => null]),
        ]);
    }

    #[Route('/{slug}', name: 'job_category_show', methods: ['GET'])]
    public function show(JobCategory $jobCategory): Response
    {
        return $this->render('job_category/show.html.twig', [
            'job_category' => $jobCategory,
        ]);
    }

}
