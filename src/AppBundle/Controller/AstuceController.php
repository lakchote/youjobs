<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Astuce;
use AppBundle\Entity\CategorieAstuce;
use AppBundle\Entity\User;
use AppBundle\Service\SetIntroMessagesAsRead;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/astuces/user/{slug}", name="astuces_user")
     */
    public function astucesUserAction(User $id, EntityManager $em, Paginator $paginator, Request $request)
    {
        $astucesForUser = $em->getRepository('AppBundle:Astuce')->findBy(['userAstuce' => $id]);
        $categoriesAstuces = $em->getRepository('AppBundle:CategorieAstuce')->findAll();
        $astuces = $paginator->paginate($astucesForUser, $request->query->getInt('page', 1),  5);
        return $this->render('default/astuces.html.twig', [
            'astuces' => $astuces,
            'categoriesAstuces' => $categoriesAstuces,
            'user' => $id
        ]);
    }

    /**
     * @Route("/astuces/favorites", name="astuces_favorites")
     */
    public function astucesFavoritesAction(TokenStorage $tokenStorage, EntityManager $em, Paginator $paginator, Request $request)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        $astucesFavorites = $em->getRepository('AppBundle:Astuce')->getAstucesFavoritesForCurrentUser($currentUser);
        $astuces = $paginator->paginate($astucesFavorites, $request->query->getInt('page', 1),  5);
        return $this->render('default/astuces.html.twig', [
            'astuces' => $astuces,
            'astucesFavorites' => true
        ]);
    }

    /**
     * @Route("/astuces/categorie/{slug}/user/{slug_user}", name="astuces_categorie_user")
     * @ParamConverter("categorie", options={"mapping" : {"slug" : "slug"}})
     * @ParamConverter("user", options={"mapping" : {"slug_user" : "slug"}})
     */
    public function astucesCategorieUserAction(CategorieAstuce $categorie, User $user, EntityManager $em, Paginator $paginator, Request $request)
    {
        $astucesForUser = $em->getRepository('AppBundle:Astuce')->getAstucesForACategorieAndAUser($categorie->getTitre(), $user);
        $categoriesAstuces = $em->getRepository('AppBundle:CategorieAstuce')->findAll();
        $astuces = $paginator->paginate($astucesForUser, $request->query->getInt('page', 1),  5);
        return $this->render('default/astuces.html.twig', [
            'astuces' => $astuces,
            'categoriesAstuces' => $categoriesAstuces,
            'user' => $user,
            'categorie' => $categorie
        ]);
    }

    /**
     * @Route("/astuces/categorie/{slug}", name="astuces_categorie")
     */
    public function astucesCategorieAction(CategorieAstuce $id, EntityManager $em, Paginator $paginator, Request $request)
    {
        $astucesForACategorie = $em->getRepository('AppBundle:Astuce')->getAstucesForACategorie($id->getTitre());
        $categoriesAstuces = $em->getRepository('AppBundle:CategorieAstuce')->findAll();
        $astuces = $paginator->paginate($astucesForACategorie, $request->query->getInt('page', 1),  5);
        return $this->render('default/astuces.html.twig', [
            'astuces' => $astuces,
            'categoriesAstuces' => $categoriesAstuces,
            'astucesFiltre' => $id
        ]);
    }

}
