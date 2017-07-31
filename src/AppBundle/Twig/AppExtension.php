<?php


namespace AppBundle\Twig;


use AppBundle\Entity\Annonce;
use AppBundle\Entity\Astuce;
use AppBundle\Entity\Categorie;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AppExtension extends \Twig_Extension
{

    private $router;
    private $tokenStorage;
    private $em;

    public function __construct(Router $router, TokenStorage $tokenStorage, EntityManager $em)
    {
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('generateAnnoncesActionsLinks', [$this, 'generateAnnoncesActionsLinks']),
            new \Twig_SimpleFilter('generateAstucesActionsLinks', [$this, 'generateAstucesActionsLinks']),
            new \Twig_SimpleFilter('countAnnoncesForACategorie', [$this, 'countAnnoncesForACategorie']),
            new \Twig_SimpleFilter('generateAnnoncesProfilLink', [$this, 'generateAnnoncesProfilLink']),
            new \Twig_SimpleFilter('generateAstucesProfilLink', [$this, 'generateAstucesProfilLink']),
            new \Twig_SimpleFilter('displayUserDescription', [$this, 'displayUserDescription']),
        ];
    }

    public function generateAnnoncesActionsLinks(Annonce $annonce)
    {
        $toReturn = '';
        $currentUser = $this->tokenStorage->getToken()->getUser();
        if ($annonce->getUser() == $currentUser) return;
        if (!in_array($annonce->getId(), $currentUser->getRemerciementsAnnonces())) {
            $toReturn = '<a href="#" class="thankUser" data-url="' . $this->router->generate('thank_user_annonce', ['id' => $annonce->getId()]) . '" data-unthank="' . $this->router->generate('unthank_user_annonce', ['id' => $annonce->getId()]) . '"><span
                                                class="fa fa-thumbs-up" aria-hidden="true"></span> Remercier</a>';
        } else {
            $toReturn = '<a href="#" class="unThankUser" data-url="' . $this->router->generate('unthank_user_annonce', ['id' => $annonce->getId()]) . '" data-thank="' . $this->router->generate('thank_user_annonce', ['id' => $annonce->getId()]) . '"><span
                                                class="fa fa-thumbs-up" aria-hidden="true"></span> Remercier</a>';
        }
        if (!in_array($annonce->getId(), $currentUser->getSignalementsAnnonces())) {
            $toReturn = $toReturn . '<a href="#" class="reportAdvert" data-url="' . $this->router->generate('annonce_report', ['id' => $annonce->getId()]) . '" data-unreport="' . $this->router->generate('annonce_unreport', ['id' => $annonce->getId()]) . '"><span
                                                class="fa fa-exclamation-triangle" aria-hidden="true"></span> Signaler</a>';
        } else {
            $toReturn = $toReturn . '<a href="#" class="unReportAdvert" data-url="' . $this->router->generate('annonce_unreport', ['id' => $annonce->getId()]) . '" data-report="' . $this->router->generate('annonce_report', ['id' => $annonce->getId()]) . '"><span
                                                class="fa fa-exclamation-triangle" aria-hidden="true"></span> Signaler</a>';
        }
        return $toReturn;
    }

    public function generateAstucesActionsLinks(Astuce $astuce)
    {
        $toReturn = '';
        $currentUser = $this->tokenStorage->getToken()->getUser();
        if ($astuce->getUserAstuce() == $currentUser) return;
        if (!in_array($astuce->getId(), $currentUser->getRemerciementsAstuces())) {
            $toReturn = '<a href="#" class="thankUserAstuce" data-url="' . $this->router->generate('thank_user_astuce', ['id' => $astuce->getId()]) . '" data-unthank="' . $this->router->generate('unthank_user_astuce', ['id' => $astuce->getId()]) . '"><span
                                                class="fa fa-thumbs-up" aria-hidden="true"></span> Remercier</a>';
        } else {
            $toReturn = '<a href="#" class="unThankUserAstuce" data-url="' . $this->router->generate('unthank_user_astuce', ['id' => $astuce->getId()]) . '" data-thank="' . $this->router->generate('thank_user_astuce', ['id' => $astuce->getId()]) . '"><span
                                                class="fa fa-thumbs-up" aria-hidden="true"></span> Remercier</a>';
        }
        if (!in_array($astuce->getId(), $currentUser->getSignalementsAstuces())) {
            $toReturn = $toReturn . '<a href="#" class="reportAstuce" data-url="' . $this->router->generate('astuce_report', ['id' => $astuce->getId()]) . '" data-unreport="' . $this->router->generate('astuce_unreport', ['id' => $astuce->getId()]) . '"><span
                                                class="fa fa-exclamation-triangle" aria-hidden="true"></span> Signaler</a>';
        } else {
            $toReturn = $toReturn . '<a href="#" class="unReportAstuce" data-url="' . $this->router->generate('astuce_unreport', ['id' => $astuce->getId()]) . '" data-report="' . $this->router->generate('astuce_report', ['id' => $astuce->getId()]) . '"><span
                                                class="fa fa-exclamation-triangle" aria-hidden="true"></span> Signaler</a>';
        }
        return $toReturn;
    }

    public function countAnnoncesForACategorie(Categorie $categorie)
    {
        return $this->em->getRepository('AppBundle:Annonce')->countAnnoncesForACategorie($categorie->getTitre());
    }

    public function generateAnnoncesProfilLink(Annonce $annonce)
    {
        $toReturn = '';
        ($annonce->getUser() == $this->tokenStorage->getToken()->getUser()) ? $route = $this->router->generate('profil') : $route = $this->router->generate('profil_user', ['id' => $annonce->getUser()->getId()]);
        ($annonce->getUser()->getPhoto()) ? $toReturn = '<a href="' . $route . '"><img class="advert__photo img img-responsive pull-left" alt="Photo de profil"
                                 src="'. $annonce->getUser()->getImgPath() . $annonce->getUser()->getPhoto()->getFileName() . '">' . $annonce->getUser()->getUsername() . '</a>' : $toReturn = '<a href="' . $route . '">' . $annonce->getUser()->getUsername() . '</a>';
        return $toReturn;
    }

    public function generateAstucesProfilLink(Astuce $astuce)
    {
        $toReturn = '';
        ($astuce->getUserAstuce() == $this->tokenStorage->getToken()->getUser()) ? $route = $this->router->generate('profil') : $route = $this->router->generate('profil_user', ['id' => $astuce->getUserAstuce()->getId()]);
        ($astuce->getUserAstuce()->getPhoto()) ? $toReturn = '<a href="' . $route . '"><img class="astuces__photo img img-responsive pull-left" alt="Photo de profil"
                                 src="'. $astuce->getUserAstuce()->getImgPath() . $astuce->getUserAstuce()->getPhoto()->getFileName() . '">' . $astuce->getUserAstuce()->getUsername() . '</a>' : $toReturn = '<a href="' . $route . '">' . $astuce->getUserAstuce()->getUsername() . '</a>';
        return $toReturn;
    }

    public function displayUserDescription(User $user)
    {
        return ($user->getContenu() == User::DESCRIPTION) ? 'L\'utilisateur n\'a pas renseigné de description pour l\'instant.' : $user->getContenu();
    }
}