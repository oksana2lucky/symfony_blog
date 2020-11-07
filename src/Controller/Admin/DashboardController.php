<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Comment;

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
            ->setTitle('Blog Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToUrl('Visit Blog', null, '/');

        yield MenuItem::section('Entities');

        yield MenuItem::subMenu('Posts', 'fa fa-article')->setSubItems([
                MenuItem::linkToCrud('Posts List', 'fa fa-file-text', Post::class)
                    ->setQueryParameter('sortField', 'created_at')
                    ->setQueryParameter('sortDirection', 'DESC'),

                MenuItem::linkToCrud('Add Post', 'fa fa-tags', Post::class)
                    ->setAction('new'),
            ]);

        yield MenuItem::subMenu('Comments', 'fa fa-article')->setSubItems([
                MenuItem::linkToCrud('Comments List', 'fa fa-file-text', Comment::class)
                    ->setQueryParameter('sortField', 'created_at')
                    ->setQueryParameter('sortDirection', 'DESC'),

                MenuItem::linkToCrud('Add Comment', 'fa fa-tags', Comment::class)
                    ->setAction('new'),
            ]);

        yield MenuItem::section('Settings');
        yield MenuItem::subMenu('Access')->setSubItems([
                MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
                MenuItem::linkToCrud('Add User', 'fa fa-tags', User::class)
                    ->setAction('new'),
            ]);
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDateTimeFormat('MMMM d, yyyy');
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        // Usually it's better to call the parent method because that gives you a
        // user menu with some menu items already created ("sign out", "exit impersonation", etc.)
        // if you prefer to create the user menu from scratch, use: return UserMenu::new()->...
        return parent::configureUserMenu($user)
            // use the given $user object to get the user name
            ->setName($user->getFullName())
            // use this method if you don't want to display the name of the user
            ->displayUserName(false)

            // you can return an URL with the avatar image
            ->setAvatarUrl('https://...')
            ->setAvatarUrl($user->getProfileImageUrl())
            // use this method if you don't want to display the user image
            ->displayUserAvatar(false)
            // you can also pass an email address to use gravatar's service
            ->setGravatarEmail($user->getMainEmailAddress())

            // you can use any type of menu item, except submenus
            ->addMenuItems([
                MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
                MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
                MenuItem::section(),
                MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            ]);
    }
}
