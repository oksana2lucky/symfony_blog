<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, ChoiceField, IdField, DateTimeField, TextareaField, TextField};
use phpDocumentor\Reflection\Types\Boolean;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextareaField::new('text');
        yield DateTimeField::new('created_at')->hideOnForm();
        yield TextField::new('edited', 'Change')->hideOnForm();
        yield AssociationField::new('user')->hideOnForm();
        yield AssociationField::new('post')->hideOnForm();
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPaginatorPageSize(30)
            ->setDefaultSort(['created_at' => 'DESC'])
            ->setSearchFields(null);
    }
}
