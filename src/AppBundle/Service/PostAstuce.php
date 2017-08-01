<?php


namespace AppBundle\Service;


use AppBundle\Entity\Astuce;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class PostAstuce
{

    private $em;
    private $tokenStorage;

    public function __construct(EntityManager $em, TokenStorage $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function createAstuce(Form $formData)
    {
        $astuce = new Astuce();
        $astuce->setIntituleAstuce($formData['intituleAstuce']->getData());
        $astuce->setCategorieAstuce($formData['categorieAstuce']->getData());
        $astuce->setContenu($formData['contenu']->getData());
        $astuce->setDatePublication(new \DateTime());
        $astuce->setUserAstuce($this->tokenStorage->getToken()->getUser());
        $this->em->persist($astuce);
        $this->em->flush();
    }
}
