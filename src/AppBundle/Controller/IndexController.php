<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\AnnonceFormType;
use AppBundle\Service\PostAnnonce;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return (!$this->getUser()) ? $this->redirectToRoute('landing_page') : $this->redirectToRoute('home');
    }

    /**
     * @Route("/lp", name="landing_page")
     */
    public function landingPageAction()
    {
        return $this->render('default/landing_page.html.twig');
    }

    /**
     * @Route("/home", name="home")
     */
    public function homeAction()
    {
        return $this->render('default/home.html.twig');
    }

    /**
     * @Route("/annonce/post", name="annonce_post")
     */
    public function annoncePostAction(Request $request, TokenStorage $tokenStorage, PostAnnonce $postAnnonce, Router $router)
    {
        $form = $this->createForm(AnnonceFormType::class);
        $form->handleRequest($request);
        if($form->isValid()) {
            $user = $tokenStorage->getToken()->getUser();
            $postAnnonce->createAdvert($form, $user);
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
}
