<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annonce;
use AppBundle\Entity\Astuce;
use AppBundle\Service\UserAnnoncesActions;
use AppBundle\Service\UserAstucesActions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserActionsController extends Controller
{
    /**
     * @Route("/thank/user/annonce/{id}", name="thank_user_annonce")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function thankUserAnnonceAction(Request $request, Annonce $id, UserAnnoncesActions $userActions, TokenStorage $tokenStorage)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        if(!$request->isXmlHttpRequest() || in_array($id->getId(), $currentUser->getRemerciementsAnnonces())) return new Response('Type de requête invalide', 400);
        $userActions->thankUserAnnonce($currentUser, $id->getUser(), $id);
        return new Response('', 200);
    }

    /**
     * @Route("/unthank/user/annonce/{id}", name="unthank_user_annonce")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function unThankUserAnnonceAction(Request $request, Annonce $id, UserAnnoncesActions $userActions, TokenStorage $tokenStorage)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $userActions->unThankUserAnnonce($currentUser, $id->getUser(), $id);
        return new Response('', 200);
    }

    /**
     * @Route("/thank/user/astuce/{id}", name="thank_user_astuce")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function thankUserAstuceAction(Request $request, Astuce $id, UserAstucesActions $userActions, TokenStorage $tokenStorage)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        if(!$request->isXmlHttpRequest() || in_array($id->getId(), $currentUser->getRemerciementsAstuces())) return new Response('Type de requête invalide', 400);
        $userActions->thankUserAstuce($currentUser, $id->getUserAstuce(), $id);
        return new Response('', 200);
    }

    /**
     * @Route("/unthank/user/astuce/{id}", name="unthank_user_astuce")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function unThankUserAstuceAction(Request $request, Astuce $id, UserAstucesActions $userActions, TokenStorage $tokenStorage)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $userActions->unThankUserAstuce($currentUser, $id);
        return new Response('', 200);
    }
}
