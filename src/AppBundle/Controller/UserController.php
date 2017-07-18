<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\ProfilPersoFormType;
use AppBundle\Form\Type\RegisterFormType;
use AppBundle\Security\LoginFormAuthenticator;
use AppBundle\Service\UserPhotoDelete;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
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

    /**
     * @Route("/profil", name="profil")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function profilAction(Request $request, TokenStorage $tokenStorage, EntityManager $em)
    {
        $form = $this->createForm(ProfilPersoFormType::class, $tokenStorage->getToken()->getUser());
        $form->handleRequest($request);
        if($form->isValid()) {
                $em->persist($form->getData());
                $em->flush();
                $this->addFlash('success','Vos modifications ont été enregistrées.');
        }
        return $this->render('user/profil_perso.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil/photo/delete", name="profil_photo_delete")
     */
    public function profilPhotoDeleteAction(UserPhotoDelete $userPhotoDelete, TokenStorage $tokenStorage, Router $router)
    {
        $userPhotoDelete->deleteUserPhotoData($tokenStorage->getToken()->getUser());
        return new RedirectResponse($router->generate('profil'));
    }
}
