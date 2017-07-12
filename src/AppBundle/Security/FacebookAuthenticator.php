<?php


namespace AppBundle\Security;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class FacebookAuthenticator extends AbstractGuardAuthenticator
{
    private $facebook;
    private $em;
    private $router;

    public function __construct(Facebook $facebook, EntityManager $em, Router $router)
    {
        $this->facebook = $facebook;
        $this->em = $em;
        $this->router = $router;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
    }

    public function getCredentials(Request $request)
    {
        if($request->getPathInfo() !== '/connect/facebook-check') {
            return;
        }

        if($code = $request->query->get('code')) {
            return $code;
        }

        throw new CustomUserMessageAuthenticationException('Il y a un problème pour accéder à Facebook.');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $accessToken = $this->facebook->getAccessToken('authorization_code', ['code' => $credentials]);
        }
        catch (IdentityProviderException $exception) {
            $response = $exception->getResponseBody();
            $errorCode = $response['error']['code'];
            throw new CustomUserMessageAuthenticationException('Il y a eu un problème lors de la connexion à Facebook : ' . $errorCode);
        }
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->facebook->getResourceOwner($accessToken);
        $email = $facebookUser->getEmail();

        if(!$user = $this->em->getRepository('AppBundle:User')->findOneBy(['email' => $email])) {
            $user = new User();
            $user->setDateInscription(new \DateTime());
            $user->setNom($facebookUser->getLastName());
            $user->setPrenom($facebookUser->getFirstName());
            $user->setEmail($facebookUser->getEmail());
            $this->em->persist($user);
            $this->em->flush();
        }
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('home'));
    }

    public function supportsRememberMe()
    {
    }
}
