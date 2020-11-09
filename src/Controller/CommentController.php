<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type\{TextareaType, SubmitType};

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/add", name="comment_add")
     */
    public function add(): Response
    {
        $formFactory = Forms::createFormFactoryBuilder()
            ->getFormFactory();

        $form = $formFactory->createBuilder(FormType::class, null, [
                'action' => '/comment/save',
                'method' => 'GET'
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Add your comment',
                'required' => true,
                'attr' => ['class' => 'textarea-field'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Send',
                'attr' => ['class' => 'button-field'],
            ])
            ->getForm();

        return $this->render('comment/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/comment/save", name="comment_save")
     * @todo implement
     */
    public function save(): Response
    {

    }
}
