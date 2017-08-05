<?php


namespace AppBundle\Manager;


use AppBundle\Entity\Astuce;
use AppBundle\Entity\Commentaires;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class CommentairesManager
{

    private $tokenStorage;
    private $em;

    public function __construct(TokenStorage $tokenStorage, EntityManager $em)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    public function postComment(Astuce $astuce, Commentaires $formData)
    {
        $formData->setAstuceCommentaires($astuce);
        $formData->setUserCommentaires($this->tokenStorage->getToken()->getUser());
        $this->em->persist($formData);
        $this->em->flush();
    }

    public function answerComment(Commentaires $commentaire, Commentaires $formData, Astuce $astuce)
    {
        $formData->setUserCommentaires($this->tokenStorage->getToken()->getUser());
        $formData->setParent($commentaire);
        $formData->setAstuceCommentaires($astuce);
        $this->em->persist($formData);
        $this->em->flush();
    }
}
