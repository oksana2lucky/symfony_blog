<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentFormType;

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
            'newest_posts' => $newestPosts,
            'popular_posts' => $popularPosts,
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_view")
     * @param $id
     * @return Response
     */
    public function view($id): Response
    {
        $post = $this->getDoctrine()->getRepository(Post::class)
            ->find($id);

        return $this->render('post/view.html.twig', [
            'post' => $post,
        ]);
    }
}
