<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\{IdField, TextField, EmailField};

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('full_name');
        yield EmailField::new('email');
        yield TextField::new('username');
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPaginatorPageSize(30)
            ->setDefaultSort(['id' => 'DESC'])
            ->setSearchFields(['username', 'email', 'first_name', 'last_name']);
    }
}
