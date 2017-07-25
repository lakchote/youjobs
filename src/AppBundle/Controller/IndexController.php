<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    public function homeAction(EntityManager $em)
    {
        $annonces = $em->getRepository('AppBundle:Annonce')->findBy([], ['datePublication' => 'DESC'] );
        return $this->render('default/home.html.twig', [
            'annonces' => $annonces
        ]);
    }
}
