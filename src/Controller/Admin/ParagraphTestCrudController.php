<?php

namespace App\Controller\Admin;

use App\Entity\ParagraphTest;
use App\Form\ProductType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ParagraphTestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ParagraphTest::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            CollectionField::new('images')
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(false)
                ->setEntryType(ProductType::class)
                ->setFormTypeOptions([
                    'by_reference' => 'false'
                ])->onlyOnForms()
                ->setTranslationParameters(['form.label.delete'=> 'Supprimer']),
            AssociationField::new('images')
                ->onlyOnIndex()
                ->setTemplatePath('admin/field/product_images.html.twig')
        ];
    }

}
