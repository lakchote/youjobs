<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\ForgottenPasswordFormType;
use AppBundle\Form\Type\ProfilPersoFormType;
use AppBundle\Form\Type\RegisterFormType;
use AppBundle\Form\Type\ResetPasswordFormType;
use AppBundle\Security\LoginFormAuthenticator;
use AppBundle\Service\ResetPassword;
use AppBundle\Service\SendMail;
use AppBundle\Service\UserPhotoDelete;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
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
     * @Method("GET")
     */
    public function loginAction()
    {
    }

    /**
     * @Route("/logout", name="logout")
     * @Method("GET")
     */
    public function logoutAction()
    {
    }

    /**
     * @Route("/register", name="register")
     * @Method({"GET","POST"})
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
     * @Route("/mdpOublieSend", name="mdp_oublie_send")
     * @Method({"GET", "POST"})
     */
    public function mdpOublieSendAction(Request $request, SendMail $sendMail)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(ForgottenPasswordFormType::class);
        $form->handleRequest($request);
        if($form->isValid()) {
            $data = $form->getData();
            $sendMail->sendResetPasswordMail($data);
            return (new Response())->setContent('Un email contenant les instructions à suivre pour réinitialiser votre mot de passe vous a été envoyé.');
        }
        else {
            return (new Response())
                ->setStatusCode(401)
                ->setContent($this->renderView('modal/mdp_oublie.html.twig', [
                    'form' => $form->createView()
                ]));
        }
    }

    /**
     * @Route("/profil", name="profil")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     * @Method({"GET","POST"})
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
     * @Route("/profil/user/{slug}", name="profil_user")
     * @Method("GET")
     */
    public function profilUserAction(User $id, TokenStorage $tokenStorage)
    {
        return ($tokenStorage->getToken()->getUser() == $id) ? $this->redirectToRoute('profil') :
            $this->render('user/profil_user.html.twig', [
                'user' => $id
            ]);
    }


    /**
     * @Route("/profil/photo/delete", name="profil_photo_delete")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     * @Method("GET")
     */
    public function profilPhotoDeleteAction(UserPhotoDelete $userPhotoDelete, TokenStorage $tokenStorage, Router $router, Request $request)
    {
        $baseUrl = explode('/', $request->headers->get('referer'));
        if(!isset($baseUrl[3]) || (isset($baseUrl[3]) && $baseUrl[3] !== substr($router->generate('profil'), 1))) return new RedirectResponse($router->generate('home'));
        $userPhotoDelete->deleteUserPhotoData($tokenStorage->getToken()->getUser());
        return new RedirectResponse($router->generate('profil'));
    }


    /**
     * @Route("/resetPassword", name="reset_password")
     * @Method({"GET","POST"})
     */
    public function resetPasswordAction(Request $request, EntityManager $em, GuardAuthenticatorHandler $guard, LoginFormAuthenticator $authenticator, ResetPassword $resetPassword)
    {
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        if($form->isValid()) {
            $data = $form->getData();
            $user = $em->getRepository('AppBundle:User')->findOneBy(['email' => $data['email']]);
            if(!$resetPassword->changePassword($user, $data)) {
                $error = new FormError('La chaîne de caractères n\'est pas la bonne. Veuillez vérifier votre email.');
                $form->get('resetPassword')->addError($error);
            } else {
                $guard->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main');
                $this->addFlash('success', 'Votre mot de passe a été changé.');
                return $this->redirectToRoute('home');
            }
        }
        return $this->render('default/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
