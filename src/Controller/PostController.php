<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Post;
use App\Entity\Comment;

class PostController extends AbstractController
{
    public const LIMIT_POSTS_BLOCK = 5;
    public const POSTS_PER_PAGE = 8;
    public const COMMENTS_PER_PAGE = 8;

    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);

        $newestPosts = $postRepository
            ->findBy(
                [],
                ['created_at' => 'DESC'],
                self::LIMIT_POSTS_BLOCK
            );

        $popularPosts = $postRepository->findByMoreCommentsCount(self::LIMIT_POSTS_BLOCK);

        return $this->render('post/index.html.twig', [
            'newest_posts' => $newestPosts,
            'popular_posts' => $popularPosts,
        ]);
    }

    /**
     * @Route("/post/all", name="post_all")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function showAll(Request $request, PaginatorInterface $paginator): Response
    {
        $allPosts = $this->getDoctrine()->getRepository(Post::class)->findBy(
            [],
            ['created_at' => 'DESC']
        );

        $posts = $paginator->paginate(
            $allPosts,
            $request->query->getInt('page', 1),
            self::POSTS_PER_PAGE
        );

        return $this->render('post/all.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_view")
     * @param $id
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function view($id, Request $request, PaginatorInterface $paginator): Response
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        $comments = $paginator->paginate(
            $post->getComments(),
            $request->query->getInt('page', 1),
            self::COMMENTS_PER_PAGE
        );

        return $this->render('post/view.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }
}
