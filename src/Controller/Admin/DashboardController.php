<?php

namespace App\Controller\Admin;

use App\Entity\Gallery;
use App\Entity\Job;
use App\Entity\JobArticle;
use App\Entity\JobCategory;
use App\Entity\ParagraphTest;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfony France Project');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Métiers'),
            MenuItem::linkToCrud('Articles', 'fa fa-newspaper', JobArticle::class),
            MenuItem::linkToCrud('Gallery', 'fa fa-newspaper', Gallery::class),

            MenuItem::section('Administration'),
            MenuItem::linkToCrud('Images', 'fa fa-images', Product::class),
            MenuItem::linkToCrud('Entrepreneurs', 'fa fa-briefcase', Job::class),
            MenuItem::linkToCrud('Categories', 'fa fa-tags', JobCategory::class),
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class),
        ];
    }
}
