<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Astuce;
use AppBundle\Entity\Commentaires;
use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Form\Type\AnnonceFormType;
use AppBundle\Form\Type\AstuceFormType;
use AppBundle\Form\Type\MessageFormType;
use AppBundle\Form\Type\NewCommentFormType;
use AppBundle\Form\Type\RegisterFormType;
use AppBundle\Form\Type\LoginFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserModalController extends Controller
{
    /**
     * @Route("/modal/login", name="modal_login")
     */
    public function modalLoginAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(LoginFormType::class);
        return $this->render('modal/login.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modal/register", name="modal_register")
     */
    public function modalRegisterAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(RegisterFormType::class);
        return $this->render('modal/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modal/postAdvert", name="modal_post_advert")
     */
    public function modalPostAdvertAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(AnnonceFormType::class);
        return $this->render('modal/postAdvert.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modal/postAtip", name="modal_post_tip")
     */
    public function modalPostTipAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(AstuceFormType::class);
        return $this->render('modal/postAtip.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modal/astuce/{id}/postAcomment", name="modal_post_comment")
     */
    public function modalPostCommentAction(Astuce $id, Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(NewCommentFormType::class);
        return $this->render('modal/postAcomment.html.twig', [
            'form' => $form->createView(),
            'astuce' => $id->getId()
        ]);
    }

    /**
     * @Route("/modal/astuce/{id}/answerComment/{comment_id}", name="modal_answer_comment")
     */
    public function modalAnswerCommentAction(Astuce $id, Commentaires $comment_id, Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(NewCommentFormType::class);
        return $this->render('modal/answerComment.html.twig', [
            'form' => $form->createView(),
            'commentaire' => $comment_id,
            'astuce' => $id->getId()
        ]);
    }

    /**
     * @Route("/modal/message/beneficiaire/{slug}", name="modal_message")
     */
    public function modalMessageAction(User $id, Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(MessageFormType::class);
        return $this->render('modal/send_message.html.twig', [
            'form' => $form->createView(),
            'user' => $id
        ]);
    }

    /**
     * @Route("/modal/answer/{id}/message/{slug}", name="modal_answer_message")
     * @ParamConverter("user", options={"mapping" : {"slug" : "slug"}})
     */
    public function modalAnswerMessageAction(Message $id, User $user, Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(MessageFormType::class);
        return $this->render('modal/send_message.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'message' => $id
        ]);
    }
}
