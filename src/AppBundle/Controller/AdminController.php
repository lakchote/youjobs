<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annonce;
use AppBundle\Entity\Astuce;
use AppBundle\Entity\User;
use AppBundle\Manager\AdminManager;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin/signalementsAnnonces", name="signalements_annonces")
     */
    public function signalementsAnnoncesAction(EntityManager $em)
    {
        $annoncesSignalees = $em->getRepository('AppBundle:Annonce')->findBy(['annonceSignalee' => true], ['nbSignalements' => 'DESC']);
        return $this->render('admin/signalements_annonces.html.twig', [
            'annonces' => $annoncesSignalees
        ]);
    }

    /**
     * @Route("/admin/signalementsAstuces", name="signalements_astuces")
     */
    public function signalementsAstucesAction(EntityManager $em)
    {
        $astucesSignalees = $em->getRepository('AppBundle:Astuce')->findBy(['astuceSignalee' => true], ['nbSignalements' => 'DESC']);
        return $this->render('admin/signalements_astuces.html.twig', [
            'astuces' => $astucesSignalees
        ]);
    }

    /**
     * @Route("/admin/signalement/remove/annonce/{id}", name="remove_signalement_annonce")
     */
    public function removeSignalementAnnonceAction(Annonce $annonce, AdminManager $adminManager)
    {
        $adminManager->removeSignalementAnnonce($annonce);
        $this->addFlash('success',  'Le signalement a été supprimé.');
        return $this->redirectToRoute('signalements_annonces');
    }

    /**
     * @Route("/admin/signalement/remove/astuce/{id}", name="remove_signalement_astuce")
     */
    public function removeSignalementAstuceAction(Astuce $astuce, AdminManager $adminManager)
    {
        $adminManager->removeSignalementAstuce($astuce);
        $this->addFlash('success',  'Le signalement a été supprimé.');
        return $this->redirectToRoute('signalements_astuces');
    }

    /**
     * @Route("/admin/remove/annonce/{id}", name="remove_annonce")
     */
    public function removeAnnonceAction(Annonce $annonce, AdminManager $adminManager)
    {
        $adminManager->removeAnnonce($annonce);
        $this->addFlash('success',  'L\'annonce a été supprimée.');
        return $this->redirectToRoute('signalements_annonces');
    }

    /**
     * @Route("/admin/remove/astuce/{id}", name="remove_astuce")
     */
    public function removeAstuceAction(Astuce $astuce, AdminManager $adminManager)
    {
        $adminManager->removeAstuce($astuce);
        $this->addFlash('success', 'L\'astuce a été supprimée.');
        return $this->redirectToRoute('signalements_astuces');
    }

    /**
     * @Route("/admin/user/remove/{id}/{referrer}", name="remove_user")
     */
    public function removeUserAction(User $user, AdminManager $adminManager, $referrer)
    {
        $adminManager->removeUser($user);
        $this->addFlash('success', 'L\'utilisateur a été supprimé.');
        return ($referrer === 'annonce') ? $this->redirectToRoute('signalements_annonces') : $this->redirectToRoute('signalements_astuces');
    }
}
