<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;

class PostController extends AbstractController
{
    public const LIMIT_POSTS = 8;
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        $postRepository =  $this->getDoctrine()->getRepository(Post::class);

        $newestPosts = $postRepository
            ->findBy(
                [],
                ['created_at' => 'DESC'],
                self::LIMIT_POSTS
            );

        $popularPosts = $postRepository->findByMoreCommentsCount(self::LIMIT_POSTS);

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'newest_posts' => $newestPosts,
            'popular_posts' => $popularPosts,
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_view")
     */
    public function view(): Response
    {

    }
}
