<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ContactFormType;
use AppBundle\Service\SendMail;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return (!$this->getUser()) ? $this->redirectToRoute('landing_page') : $this->redirectToRoute('home');
    }

    /**
     * @Route("/lp", name="landing_page")
     * @Method("GET")
     */
    public function landingPageAction()
    {
        return $this->render('default/landing_page.html.twig');
    }

    /**
     * @Route("/home", name="home")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     * @Method("GET")
     */
    public function homeAction(EntityManager $em, Paginator $paginator, Request $request)
    {
        $sortAnnoncesByDatePublication = $em->getRepository('AppBundle:Annonce')->findBy([], ['datePublication' => 'DESC'] );
        $categories = $em->getRepository('AppBundle:Categorie')->findAll();
        $annonces = $paginator->paginate($sortAnnoncesByDatePublication, $request->query->getInt('page', 1),  5);
        return $this->render('default/home.html.twig', [
            'annonces' => $annonces,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/astuces", name="astuces")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     * @Method("GET")
     */
    public function astucesAction(EntityManager $em, Paginator $paginator, Request $request)
    {
        $sortAstucesByDatePublication = $em->getRepository('AppBundle:Astuce')->findBy([], ['datePublication' => 'DESC']);
        $categoriesAstuces = $em->getRepository('AppBundle:CategorieAstuce')->findAll();
        $astuces = $paginator->paginate($sortAstucesByDatePublication, $request->query->getInt('page', 1), 5);
        return $this->render('default/astuces.html.twig', [
            'astuces' => $astuces,
            'categoriesAstuces' => $categoriesAstuces
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @Method({"GET","POST"})
     */
    public function contactAction(Request $request, SendMail $sendMail)
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $data = $form->getData();
            $sendMail->sendContactMail($data);
            $this->addFlash('success', 'Nous avons reçu votre mail et vous répondrons dans les plus brefs délais.');
            return $this->redirectToRoute('home');
        }
        return $this->render('default/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/search", name="search")
     * @Method("GET")
     */
    public function searchAction(Request $request, EntityManager $em, Paginator $paginator)
    {
        $searchTerm = $request->query->get('searchTerm');
        $nbResults = $em->getRepository('AppBundle:Annonce')->countSearchResultsForTerm($searchTerm);
        $searchResults = $em->getRepository('AppBundle:Annonce')->getSearchResultsForTerm($searchTerm);
        $annonces = $paginator->paginate($searchResults, $request->query->getInt('page', 1), 5);
        return $this->render('default/search_results.html.twig', [
            'nbResults' => $nbResults,
            'annonces' => $annonces,
            'searchTerm' => $searchTerm
        ]);
    }
}
