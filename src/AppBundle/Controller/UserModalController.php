<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Astuce;
use AppBundle\Entity\Commentaires;
use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Form\Type\AnnonceFormType;
use AppBundle\Form\Type\AstuceFormType;
use AppBundle\Form\Type\ForgottenPasswordFormType;
use AppBundle\Form\Type\MessageFormType;
use AppBundle\Form\Type\NewCommentFormType;
use AppBundle\Form\Type\RegisterFormType;
use AppBundle\Form\Type\LoginFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserModalController extends Controller
{
    /**
     * @Route("/modal/login", name="modal_login")
     * @Method("GET")
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
     * @Route("/modal/mdpOublie", name="modal_mdpOublie")
     * @Method("GET")
     */
    public function modalMdpOublieAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(ForgottenPasswordFormType::class);
        return $this->render('modal/mdp_oublie.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modal/register", name="modal_register")
     * @Method("GET")
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
     * @Method("GET")
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
     * @Method("GET")
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
     * @Method("GET")
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
     * @Method("GET")
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
     * @Method("GET")
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
     * @Method("GET")
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
