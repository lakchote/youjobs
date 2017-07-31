<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Astuce;
use AppBundle\Entity\User;
use AppBundle\Form\Type\AstuceFormType;
use AppBundle\Service\PostAstuce;
use AppBundle\Service\SetIntroMessagesAsRead;
use AppBundle\Service\UserAstucesActions;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
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
     * @Route("/astuce/unreport/{id}", name="astuce_unreport")
     */
    public function astuceUnreportAction(Request $request, Astuce $id, UserAstucesActions $userActions, TokenStorage $tokenStorage)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $userActions->unReportAstuce($currentUser, $id);
        return new Response('', 200);
    }

    /**
     * @Route("/astuce/view/{id}", name="astuce_view")
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     */
    public function astuceViewAction(Astuce $id)
    {
        return $this->render('default/astuce_view.html.twig', [
            'astuce' => $id
        ]);
    }

    /**
     * @Route("/astuces/user/{id}", name="astuces_user")
     */
    public function astucesUserAction(User $id, EntityManager $em, Paginator $paginator, Request $request)
    {
        $astucesForUser = $em->getRepository('AppBundle:Astuce')->findBy(['userAstuce' => $id]);
        $astuces = $paginator->paginate($astucesForUser, $request->query->getInt('page', 1),  5);
        return $this->render('default/astuces.html.twig', [
            'astuces' => $astuces,
            'user' => $id
        ]);
    }
}
