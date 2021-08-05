<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JobCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Job::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        // if the method is not defined in a CRUD controller, link to its route
        $sendInvoice = Action::new('sendInvoice', 'Send invoice', 'fa fa-envelope')
            ->linkToRoute('job_show', function (Job $job): array {
                return [
                    'slug' => $job->getSlug()
                ];
            });

        return $actions
            ->add(Crud::PAGE_INDEX, $sendInvoice);
    }



    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('User Details'),
            TextField::new('name'),
            AssociationField::new('category'),
            FormField::addPanel('User Details 2'),

            AssociationField::new('user'),
        ];
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            // adds the CSS and JS assets associated to the given Webpack Encore entry
            // it's equivalent to adding these inside the <head> element:
            // {{ encore_entry_link_tags('...') }} and {{ encore_entry_script_tags('...') }}
            ->addWebpackEncoreEntry('admin-app')
            ->addHtmlContentToBody('<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmUEzB_TzRJuwfOOxkyUB8bEgQZRN26-A&libraries=places&callback=initAutocomplete"></script>')
            ;

    }
}
