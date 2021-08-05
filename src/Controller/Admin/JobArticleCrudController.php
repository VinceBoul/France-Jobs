<?php

namespace App\Controller\Admin;

use App\Entity\JobArticle;
use App\Entity\User;
use App\Form\ParagraphTestType;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class JobArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobArticle::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
         //   ->overrideTemplate('crud/field/collection', 'bundles/EasyAdminBundle/form/form_theme.html.twig')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            TextField::new('title'),
            TextEditorField::new('subtitle')->setFormType(CKEditorType::class),
            CollectionField::new('paragraphs')
                ->allowAdd()->allowDelete()
                ->setEntryIsComplex(true)
                ->setEntryType(ParagraphTestType::class)
                ->setFormTypeOptions([
                    'by_reference' => 'false'
                ]),
        ];

        /**
         * @var User $user
         */
        $user = $this->getUser();
        if ($user){
            $jobs = $user->getJobs();
            if (count($jobs) === 1){
                $fields[] = AssociationField::new('job')->setValue($jobs->get(0))
                    ->setFormTypeOptions(['query_builder' => function (EntityRepository $em) {
                        return $em->createQueryBuilder('f')
                            ->where('f.user = :user')
                            ->setParameter('user', $this->getUser());
                    }]);
            }

        }

        return $fields;

  /*      return [
            TextField::new('title'),
            AssociationField::new('job')->setValue(),
            TextEditorField::new('subtitle')->setFormType(CKEditorType::class),

                       CollectionField::new('paragraphs')
                ->setEntryIsComplex(true)
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(ParagraphTestType::class)
                ->setFormTypeOptions([
                    'by_reference' => false
                ])
                ->showEntryLabel(true)
                ->onlyOnForms()
                ->setTranslationParameters(['form.label.delete'=> 'Supprimer'])

        ];*/
    }


    public function createEntity(string $entityFqcn)
    {
        $product = new JobArticle();
        /**
         * @var User $user
         */
        $user = $this->getUser();
        if ($user){
            $jobs = $user->getJobs();
            if (count($jobs) === 1){
                $product->setJob($jobs->get(0));
            }
        }

        return $product;
    }

}
