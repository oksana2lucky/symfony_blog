<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    /*public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];

        return [
            Field::new('id'),
            Field::new('username'),
            Field::new('email'),
            Field::new('password'),
            Field::new('first_name'),
            Field::new('last_name'),
            Field::new('roles'),
        ];
    }*/
}
