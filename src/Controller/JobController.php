<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobType;
use App\Kernel;
use App\Repository\JobRepository;
use App\Service\JobCronUpdater;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/job')]
class JobController extends AbstractController
{
    #[Route('/', name: 'job_index', methods: ['GET'])]
    public function index(JobRepository $jobRepository): Response
    {
        return $this->render('job/index.html.twig', [
            'jobs' => $jobRepository->findAll(),
        ]);
    }

    #[Route('/update', name: 'job_update', methods: ['GET'])]
    public function update(JobCronUpdater $jobCronUpdater): Response
    {

        $var = 1;
        $jobCronUpdater->updateJobs($var);
        return $this->render('job/index.html.twig', [
            'jobs' => []
        ]);
    }



    #[Route('/{slug}', name: 'job_show', methods: ['GET'])]
    public function show(Job $job): Response
    {
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }


}
