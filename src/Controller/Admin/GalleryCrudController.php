<?php

namespace App\Controller\Admin;

use App\Entity\FleImage;
use App\Entity\Gallery;
use App\Form\FleImageType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\FormInterface;
use Vich\UploaderBundle\Handler\UploadHandler;
use Vich\UploaderBundle\Storage\FileSystemStorage;

class GalleryCrudController extends AbstractCrudController
{
    private $uploadHandler;
    public function __construct(UploadHandler $uploadHandler)
    {
        $this->uploadHandler = $uploadHandler;
    }

    public static function getEntityFqcn(): string
    {
        return Gallery::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('body'),
            CollectionField::new('gallery_images', 'Images')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(FleImageType::class)
                ->setFormTypeOptions([
                    'by_reference' => 'false'
                ])
                ->setTranslationParameters(['form.label.delete'=>'Delete'])
                ->onlyOnForms(),
            AssociationField::new('gallery_images')
                ->onlyOnIndex()
                ->setTemplatePath('admin/field/product_images.html.twig'),
            TextField::new('slug')->onlyOnIndex()
        ];
    }

    /**
     * Utilisé pour la création des entités
     * @param EntityManagerInterface $entityManager
     * @param $entityInstance
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityManager->persist($entityInstance);
        $entityManager->flush();

        foreach ($entityInstance->getGalleryImages() as $gImg){
            $gImg->setGallery($entityInstance);
            $entityManager->persist($gImg);
        }

        $entityManager->flush();
    }

    /**
     * Utilisé pour l'édition d'une entité
     * @param EntityManagerInterface $entityManager
     * @param $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        foreach ($entityInstance->getGalleryImages() as $gImg){

            $gImg->setGallery($entityInstance);
            $entityManager->persist($gImg);

        }
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

}
