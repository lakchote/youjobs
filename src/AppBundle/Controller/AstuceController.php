<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Astuce;
use AppBundle\Form\Type\AstuceFormType;
use AppBundle\Service\PostAstuce;
use AppBundle\Service\SetIntroMessagesAsRead;
use AppBundle\Service\UserAstucesActions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class AstuceController extends Controller
{
    /**
     * @Route("/astuces/read/intro-message", name="astuces_read_intro_message")
     */
    public function astucesReadIntroMessageAction(Request $request, SetIntroMessagesAsRead $setIntroMessagesAsRead)
    {
        if (!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $setIntroMessagesAsRead->setAstucesMessageAsRead();
        return new Response('', 200);
    }

    /**
     * @Route("/astuce/post", name="astuce_post")
     */
    public function astucePostAction(Request $request, PostAstuce $postAstuce, Router $router)
    {
        if (!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(AstuceFormType::class);
        $form->handleRequest($request);
        if($form->isValid()) {
            $postAstuce->createAstuce($form);
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
     */
    public function astuceReportAction(Request $request, Astuce $id, UserAstucesActions $userActions, TokenStorage $tokenStorage)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        if(!$request->isXmlHttpRequest() || in_array($id->getId(), $currentUser->getSignalementsAstuces())) return new Response('Type de requête invalide', 400);
        $userActions->reportAstuce($currentUser, $id);
        return new Response('', 200);
    }

    /**
     * @Route("/astuce/view/{id}", name="astuce_view")
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     */
    public function annonceViewAction(Astuce $id)
    {
        return $this->render('default/astuce_view.html.twig', [
            'astuce' => $id
        ]);
    }
}
