<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function homeAction(EntityManager $em, Paginator $paginator, Request $request)
    {
        $sortAnnoncesByDatePublication = $em->getRepository('AppBundle:Annonce')->findBy([], ['datePublication' => 'DESC'] );
        $annonces = $paginator->paginate($sortAnnoncesByDatePublication, $request->query->getInt('page', 1),  5);
        return $this->render('default/home.html.twig', [
            'annonces' => $annonces
        ]);
    }

    /**
     * @Route("/astuces", name="astuces")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function astucesAction(EntityManager $em, Paginator $paginator, Request $request)
    {
        $sortAstucesByDatePublication = $em->getRepository('AppBundle:Astuce')->findBy([], ['datePublication' => 'DESC']);
        $astuces = $paginator->paginate($sortAstucesByDatePublication, $request->query->getInt('page', 1), 5);
        return $this->render('default/astuces.html.twig', [
            'astuces' => $astuces
        ]);
    }
}
