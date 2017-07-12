<?php


namespace AppBundle\Security;


use AppBundle\Form\Type\LoginFormType;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    private $router;
    private $formFactory;
    private $em;
    private $encoder;
    private $authUtils;
    private $twig;

    public function __construct(Router $router, FormFactory $formFactory, EntityManager $em, UserPasswordEncoder $encoder, AuthenticationUtils $authUtils, TwigEngine $twig)
    {
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->encoder = $encoder;
        $this->authUtils = $authUtils;
        $this->twig = $twig;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('landing_page');
    }

    public function getCredentials(Request $request)
    {
        if(!($request->getPathInfo() == '/login' && $request->isMethod('POST'))) return;
        $loginForm = $this->formFactory->create(LoginFormType::class);
        $loginForm->handleRequest($request);
        $credentials = [];
        $credentials['username'] = $loginForm['email']->getData();
        $credentials['password'] = $loginForm['password']->getData();
        $request->getSession()->set(Security::LAST_USERNAME, $credentials['username']);
        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->em->getRepository('AppBundle:User')->findOneBy([
            'email' => $credentials['username']
        ]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if($credentials['password'] === null) {
            throw new CustomUserMessageAuthenticationException('Le mot de passe ne peut être vide.');
        }
        return $this->encoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        $error = $this->authUtils->getLastAuthenticationError();
        $lastUsername = $this->authUtils->getLastUsername();
        $form = $this->formFactory->create(LoginFormType::class, ['email' => $lastUsername]);
        return (new Response())
            ->setStatusCode(401)
            ->setContent($this->twig->render('modal/login.html.twig', [
                'form' => $form->createView(),
                'error' => $error
            ]));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return (new Response)->setContent($this->router->generate('home'));
    }
}
