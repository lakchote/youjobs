<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annonce;
use AppBundle\Form\Type\AnnonceFormType;
use AppBundle\Service\PostAnnonce;
use AppBundle\Service\UserAnnoncesActions;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AnnonceActionsController extends Controller
{
    /**
     * @Route("/annonce/post", name="annonce_post")
     */
    public function annoncePostAction(Request $request, PostAnnonce $postAnnonce, Router $router)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
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
     * @Route("/annonce/delete/{id}", name="annonce_delete")
     */
    public function annonceDeleteAction(Annonce $id, UserAnnoncesActions $userActions)
    {
        $userActions->deleteAdvert($id);
        $this->addFlash('success', 'L\'annonce a été supprimée.');
        return $this->redirectToRoute('home');
    }
}
