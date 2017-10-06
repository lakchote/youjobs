<?php

namespace AppBundle\Controller;

use League\OAuth2\Client\Provider\Facebook;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OAuth2Controller extends Controller
{
    /**
     * @Route("/connect/facebook", name="connect_facebook")
     * @Method("GET")
     */
    public function connectFacebookAction(Facebook $facebook)
    {
        $url = $facebook->getAuthorizationUrl();
        return $this->redirect($url);
    }

    /**
     * @Route("/connect/facebook-check", name="connect_facebook_check")
     * @Method("GET")
     */
    public function connectFacebookCheckAction()
    {
    }
}
