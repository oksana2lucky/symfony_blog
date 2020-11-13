<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\CommentFormType;
use App\Entity\Post;
use App\Entity\Comment;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/{postId}/add", name="comment_add")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @ParamConverter("post", options={"mapping": {"postId": "id"}})
     * @param Request $request
     * @param Post $post
     * @return Response
     * @throws \Exception
     */
    public function add(Request $request, Post $post): Response
    {
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setPost($post);

        $form = $this->createForm(CommentFormType::class, $comment, [
            'action' => $this->generateUrl('comment_add', ['postId' => $post->getId()]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $request->request->get('comment_form')['text'] ?? '';
            $comment->setText($text);
            $comment->setCreatedAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('post_view', ['id' => $post->getId()]);
        }

        return $this->render('comment/add.html.twig', [
            'comment_add_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment/{commentId}/edit", name="comment_edit")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @ParamConverter("comment", options={"mapping": {"commentId": "id"}})
     * @param Request $request
     * @param Comment $comment
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentFormType::class, $comment, [
            'action' => $this->generateUrl('comment_edit', ['commentId' => $comment->getId()]),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $text = $request->request->get('comment_form')['text'] ?? '';
            $comment->setText($text);
            $comment->setUpdatedAt(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('post_view', ['id' => $comment->getPost()->getId()]);
        }

        return new JsonResponse([
            'message' => 'Success',
            'output' => $this->renderView('comment/edit.html.twig', [
                'comment_edit_form' => $form->createView(),
            ])
        ], 200);
    }
}
