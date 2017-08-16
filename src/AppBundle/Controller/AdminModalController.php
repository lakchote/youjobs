<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annonce;
use AppBundle\Entity\Astuce;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminModalController extends Controller
{
    /**
     * @Route("/admin/modal/signalement/annonce/{id}", name="admin_modal_signalement_annonce")
     */
    public function adminModalSignalementAnnonceAction(Request $request, Annonce $annonce)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        return $this->render('admin/modal/annonce_signalee.html.twig', [
            'annonce' => $annonce
        ]);
    }

    /**
     * @Route("/admin/modal/signalement/astuce/{id}", name="admin_modal_signalement_astuce")
     */
    public function adminModalSignalementAstuceAction(Request $request, Astuce $astuce)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        return $this->render('admin/modal/astuce_signalee.html.twig', [
            'astuce' => $astuce
        ]);
    }
}
