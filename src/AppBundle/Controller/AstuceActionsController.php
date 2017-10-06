<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Astuce;
use AppBundle\Entity\Commentaires;
use AppBundle\Form\Type\AstuceFormType;
use AppBundle\Form\Type\NewCommentFormType;
use AppBundle\Manager\CommentairesManager;
use AppBundle\Service\PostAstuce;
use AppBundle\Service\UserAstucesActions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AstuceActionsController extends Controller
{
    /**
     * @Route("/astuce/post", name="astuce_post")
     * @Method({"GET","POST"})
     */
    public function astucePostAction(Request $request, PostAstuce $postAstuce, Router $router)
    {
        if (!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(AstuceFormType::class);
        $form->handleRequest($request);
        if($form->isValid()) {
            $postAstuce->createAstuce($form->getData());
            $this->addFlash('success', 'Votre astuce a été publiée.');
            return (new Response)->setContent($router->generate('astuces'), 200);
        } else {
            return (new Response())
                ->setStatusCode(400)
                ->setContent($this->renderView('modal/postAtip.html.twig', [
                    'form' => $form->createView()
                ]));
        }
    }

    /**
     * @Route("/astuce/report/{id}", name="astuce_report")
     * @Method("GET")
     */
    public function astuceReportAction(Request $request, Astuce $id, UserAstucesActions $userActions, TokenStorage $tokenStorage)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        if(!$request->isXmlHttpRequest() || in_array($id->getId(), $currentUser->getSignalementsAstuces())) return new Response('Type de requête invalide', 400);
        $userActions->reportAstuce($currentUser, $id);
        return new Response('', 200);
    }

    /**
     * @Route("/astuce/unreport/{id}", name="astuce_unreport")
     * @Method("GET")
     */
    public function astuceUnreportAction(Request $request, Astuce $id, UserAstucesActions $userActions, TokenStorage $tokenStorage)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $userActions->unReportAstuce($currentUser, $id);
        return new Response('', 200);
    }

    /**
     * @Route("/astuce/bookmark/{id}", name="astuce_bookmark")
     * @Method("GET")
     */
    public function astuceBookmarkAction(Astuce $id, TokenStorage $tokenStorage, Request $request, UserAstucesActions $userAstuces)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $currentUser = $tokenStorage->getToken()->getUser();
        $userAstuces->bookmarkAstuce($currentUser, $id);
        return new Response('', 200);
    }

    /**
     * @Route("/astuce/unbookmark/{id}", name="astuce_unbookmark")
     * @Method("GET")
     */
    public function astuceUnbookmarkAction(Astuce $id, TokenStorage $tokenStorage, Request $request, UserAstucesActions $userAstuces)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $currentUser = $tokenStorage->getToken()->getUser();
        $userAstuces->unBookmarkAstuce($currentUser, $id);
        return new Response('', 200);
    }

    /**
     * @Route("/astuce/{id}/post/comment", name="astuce_post_comment")
     * @Method({"GET","POST"})
     */
    public function astucePostCommentAction(Astuce $id, Request $request, CommentairesManager $commentsManager, Router $router)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(NewCommentFormType::class);
        $form->handleRequest($request);
        if($form->isValid()) {
            $commentsManager->postComment($id, $form->getData());
            $this->addFlash('success', 'Votre commentaire a été publié.');
            return (new Response())->setContent($router->generate('astuces'), 200);
        } else {
            return (new Response())
                ->setStatusCode(400)
                ->setContent($this->renderView('modal/postAcomment.html.twig', [
                    'form' => $form->createView()
                ]));
        }
    }

    /**
     * @Route("/astuce/{id}/answer/comment/{comment_id}", name="astuce_answer_comment")
     * @Method({"GET","POST"})
     */
    public function astuceAnswerCommentAction(Astuce $id, Commentaires $comment_id, Request $request, CommentairesManager $commentsManager, Router $router)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(NewCommentFormType::class);
        $form->handleRequest($request);
        if($form->isValid()) {
            $commentsManager->answerComment($comment_id, $form->getData(), $id);
            $this->addFlash('success', 'Votre commentaire a été publié.');
            return (new Response())->setContent($router->generate('astuce_view', ['id' => $id->getId()]), 200);
        } else {
            return (new Response())
                ->setStatusCode(400)
                ->setContent($this->renderView('modal/answerComment.html.twig', [
                    'form' => $form->createView(),
                    'commentaire' => $comment_id,
                    'astuce' => $id->getId()
                ]));
        }
    }

    /**
     * @Route("/astuce/delete/{id}", name="astuce_delete")
     * @Method("GET")
     */
    public function annonceDeleteAction(Astuce $id, UserAstucesActions $userActions)
    {
        $userActions->deleteAstuce($id);
        $this->addFlash('success', 'L\'astuce a été supprimée.');
        return $this->redirectToRoute('astuces');
    }
}
