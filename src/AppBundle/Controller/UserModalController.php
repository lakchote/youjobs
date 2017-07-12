<?php

namespace AppBundle\Controller;

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
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide');
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
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide');
        $form = $this->createForm(RegisterFormType::class);
        return $this->render('modal/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
