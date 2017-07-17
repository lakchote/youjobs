<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\RegisterFormType;
use AppBundle\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class UserController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request, EntityManager $em, Router $router, LoginFormAuthenticator $guard, GuardAuthenticatorHandler $guardHandler)
    {
        if(!$request->isMethod('POST')) return new Response('Type de requête invalide');
        $form = $this->createForm(RegisterFormType::class);
        $form->handleRequest($request);
        if($form->isValid()){
            $em->persist($form->getData());
            $em->flush();
            $user = $em->getRepository(User::class)->findOneBy(['email' => $form['email']->getData()]);
            $guardHandler->authenticateUserAndHandleSuccess($user, $request, $guard, 'main');
            return (new Response())
                    ->setContent($router->generate('home'));
        } else {
            return (new Response())
                ->setStatusCode('400')
                ->setContent($this->renderView('modal/register.html.twig', [
                'form' => $form->createView()
            ]));
        }
    }
}
