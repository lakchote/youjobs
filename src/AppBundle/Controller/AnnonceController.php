<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annonce;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\User;
use AppBundle\Service\SetIntroMessagesAsRead;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class AnnonceController extends Controller
{
    /**
     * @Route("/annonce/view/{id}", name="annonce_view")
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     * @Method("GET")
     */
    public function annonceViewAction(Annonce $id)
    {
        return $this->render('default/annonce_view.html.twig', [
            'annonce' => $id
        ]);
    }

    /**
     * @Route("/annonces/read/intro-message", name="annonces_read_intro_message")
     * @Method("GET")
     */
    public function annoncesReadIntroMessageAction(Request $request, SetIntroMessagesAsRead $setIntroMessagesAsRead)
    {
        if (!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $setIntroMessagesAsRead->setAnnoncesMessageAsRead();
        return new Response('', 200);
    }

    /**
     * @Route("/annonces/categorie/{slug}", name="annonces_categorie")
     * @Method("GET")
     */
    public function annoncesCategorieAction(Categorie $id, EntityManager $em, Paginator $paginator, Request $request)
    {
        $annoncesForACategorie = $em->getRepository('AppBundle:Annonce')->getAnnoncesForACategorie($id->getTitre());
        $categories = $em->getRepository('AppBundle:Categorie')->findAll();
        $annonces = $paginator->paginate($annoncesForACategorie, $request->query->getInt('page', 1),  5);
        return $this->render('default/home.html.twig', [
            'annonces' => $annonces,
            'categories' => $categories,
            'categorieFiltre' => $id
        ]);
    }

    /**
     * @Route("/annonces/user/{slug}", name="annonces_user")
     * @Method("GET")
     */
    public function annoncesUserAction(User $id, EntityManager $em, Paginator $paginator, Request $request)
    {
        $annoncesForUser = $em->getRepository('AppBundle:Annonce')->findBy(['user' => $id]);
        $categories = $em->getRepository('AppBundle:Categorie')->findAll();
        $annonces = $paginator->paginate($annoncesForUser, $request->query->getInt('page', 1),  5);
        return $this->render('default/home.html.twig', [
            'annonces' => $annonces,
            'categories' => $categories,
            'user' => $id
        ]);
    }

    /**
     * @Route("/annonces/categorie/{slug}/user/{slug_user}", name="annonces_categorie_user")
     * @ParamConverter("categorie", options={"mapping": {"slug" : "slug"}})
     * @ParamConverter("user", options={"mapping" : {"slug_user" : "slug"}})
     * @Method("GET")
     */
    public function annoncesCategorieUserAction(Categorie $categorie, User $user, EntityManager $em, Paginator $paginator, Request $request)
    {
        $annoncesForUser = $em->getRepository('AppBundle:Annonce')->getAnnoncesForACategorieAndAUser($categorie->getTitre(), $user);
        $categories = $em->getRepository('AppBundle:Categorie')->findAll();
        $annonces = $paginator->paginate($annoncesForUser, $request->query->getInt('page', 1),  5);
        return $this->render('default/home.html.twig', [
            'annonces' => $annonces,
            'categories' => $categories,
            'user' => $user,
            'categorie' => $categorie
        ]);
    }
}
