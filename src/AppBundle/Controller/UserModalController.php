<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\AnnonceFormType;
use AppBundle\Form\Type\AstuceFormType;
use AppBundle\Form\Type\RegisterFormType;
use AppBundle\Form\Type\LoginFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserModalController extends Controller
{
    /**
     * @Route("/modal/login", name="modal_login")
     */
    public function modalLoginAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(LoginFormType::class);
        return $this->render('modal/login.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modal/register", name="modal_register")
     */
    public function modalRegisterAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(RegisterFormType::class);
        return $this->render('modal/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modal/postAdvert", name="modal_post_advert")
     */
    public function modalPostAdvertAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(AnnonceFormType::class);
        return $this->render('modal/postAdvert.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modal/postAtip", name="modal_post_tip")
     */
    public function modalPostTipAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(AstuceFormType::class);
        return $this->render('modal/postAtip.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
