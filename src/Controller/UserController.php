<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index(): Response
    {
        $roles = $this->getUser()->getRoles();
        $role = in_array('ROLE_ADMIN', $roles) ? 'admin' : (in_array('ROLE_EDITOR', $roles) ? 'editor' : '');
        return $this->render('user/index.html.twig', [
            'role' => $role,
        ]);
    }
}
