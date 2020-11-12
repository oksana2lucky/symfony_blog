<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\CommentFormType;
use App\Entity\Post;
use App\Entity\Comment;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/{postId}/add", name="comment_add")
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
            'comment_form' => $form->createView(),
        ]);
    }
}
