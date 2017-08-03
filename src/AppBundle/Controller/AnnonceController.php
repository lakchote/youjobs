<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annonce;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\User;
use AppBundle\Form\Type\AnnonceFormType;
use AppBundle\Service\PostAnnonce;
use AppBundle\Service\SetIntroMessagesAsRead;
use AppBundle\Service\UserAnnoncesActions;
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
class AnnonceController extends Controller
{
    /**
     * @Route("/annonce/post", name="annonce_post")
     */
    public function annoncePostAction(Request $request, TokenStorage $tokenStorage, PostAnnonce $postAnnonce, Router $router)
    {
        $form = $this->createForm(AnnonceFormType::class);
        $form->handleRequest($request);
        if($form->isValid()) {
            $postAnnonce->createAdvert($form->getData());
            $this->addFlash('success', 'Votre annonce a été publiée.');
            return (new Response())->setContent($router->generate('home'), 200);
        } else {
            return (new Response())
                ->setStatusCode(400)
                ->setContent($this->renderView('modal/postAdvert.html.twig', [
                    'form' => $form->createView()
                ]));
        }
    }

    /**
     * @Route("/annonce/fulltext/{id}", name="annonce_fulltext")
     */
    public function annonceFullTextAction(Request $request, EntityManager $em, Annonce $id)
    {
        if(!$request->isXmlHttpRequest() || !$annonce = $em->getRepository('AppBundle:Annonce')->find($id)) return new Response('Type de requête invalide', 400);
        return (new Response())->setContent($annonce->getContenu());
    }

    /**
     * @Route("/annonce/view/{id}", name="annonce_view")
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     */
    public function annonceViewAction(Annonce $id)
    {
        return $this->render('default/annonce_view.html.twig', [
            'annonce' => $id
        ]);
    }

    /**
     * @Route("/annonce/report/{id}", name="annonce_report")
     */
    public function annonceReportAction(Request $request, Annonce $id, UserAnnoncesActions $userActions, TokenStorage $tokenStorage)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        if(!$request->isXmlHttpRequest() || in_array($id->getId(), $currentUser->getSignalementsAnnonces())) return new Response('Type de requête invalide', 400);
        $userActions->reportAdvert($currentUser, $id);
        return new Response('', 200);
    }

    /**
     * @Route("/annonce/unreport/{id}", name="annonce_unreport")
     */
    public function annonceUnreportAction(Request $request, Annonce $id, UserAnnoncesActions $userActions, TokenStorage $tokenStorage)
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $userActions->unReportAdvert($currentUser, $id);
        return new Response('', 200);
    }

    /**
     * @Route("/annonces/read/intro-message", name="annonces_read_intro_message")
     */
    public function annoncesReadIntroMessageAction(Request $request, SetIntroMessagesAsRead $setIntroMessagesAsRead)
    {
        if (!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $setIntroMessagesAsRead->setAnnoncesMessageAsRead();
        return new Response('', 200);
    }

    /**
     * @Route("/annonces/categorie/{id}", name="annonces_categorie")
     */
    public function annoncesCategorieAction(Categorie $id, EntityManager $em, Paginator $paginator, Request $request)
    {
        $annoncesForACategorie = $em->getRepository('AppBundle:Annonce')->getAnnoncesForACategorie($id->getTitre());
        $categories = $em->getRepository('AppBundle:Categorie')->findAll();
        $annonces = $paginator->paginate($annoncesForACategorie, $request->query->getInt('page', 1),  5);
        return $this->render('default/home.html.twig', [
            'annonces' => $annonces,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/annonces/user/{id}", name="annonces_user")
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
     * @Route("/annonces/categorie/{categorie}/user/{id}", name="annonces_categorie_user")
     */
    public function annoncesCategorieUserAction(Categorie $categorie, User $id, EntityManager $em, Paginator $paginator, Request $request)
    {
        $annoncesForUser = $em->getRepository('AppBundle:Annonce')->getAnnoncesForACategorieAndAUser($categorie->getTitre(), $id);
        $categories = $em->getRepository('AppBundle:Categorie')->findAll();
        $annonces = $paginator->paginate($annoncesForUser, $request->query->getInt('page', 1),  5);
        return $this->render('default/home.html.twig', [
            'annonces' => $annonces,
            'categories' => $categories,
            'user' => $id,
            'categorie' => $categorie
        ]);
    }

    /**
     * @Route("/annonce/delete/{id}", name="annonce_delete")
     */
    public function annonceDeleteAction(Annonce $id, UserAnnoncesActions $userActions)
    {
        $userActions->deleteAdvert($id);
        return $this->redirectToRoute('home');
    }
}
