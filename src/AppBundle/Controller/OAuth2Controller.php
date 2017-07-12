<?php

namespace AppBundle\Controller;

use League\OAuth2\Client\Provider\Facebook;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OAuth2Controller extends Controller
{
    /**
     * @Route("/connect/facebook", name="connect_facebook")
     */
    public function connectFacebookAction(Facebook $facebook, Request $request)
    {
        $url = $facebook->getAuthorizationUrl();
        return $this->redirect($url);
    }

    /**
     * @Route("/connect/facebook-check", name="connect_facebook_check")
     */
    public function connectFacebookCheckAction()
    {
    }
}
