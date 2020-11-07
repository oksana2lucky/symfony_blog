<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, TextField, TextEditorField, DateTimeField, AssociationField};

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFields(string $pageName): iterable
    {
         yield IdField::new('id')->hideOnForm();
         yield TextField::new('title');
         yield TextEditorField::new('description');
         yield TextEditorField::new('body')->onlyOnForms();
         yield DateTimeField::new('created_at')->hideOnForm();
         yield DateTimeField::new('updated_at')->hideOnForm();
         yield AssociationField::new('user')->hideOnForm();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPaginatorPageSize(20)
            ->setDefaultSort(['created_at' => 'DESC', 'title' => 'ASC'])
            ->setSearchFields(['title', 'description']);
    }
}
