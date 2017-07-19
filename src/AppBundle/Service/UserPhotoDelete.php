<?php


namespace AppBundle\Service;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\File;

class UserPhotoDelete
{

    private $em;
    private $directory;

    public function __construct(EntityManager $em, $directory)
    {
        $this->em = $em;
        $this->directory = $directory;
    }

    public function deleteUserPhotoData(User $user)
    {
        if(!$user->getPhoto() instanceof File) return;
        $oldDir = getcwd();
        chdir($this->directory);
        unlink($user->getPhoto());
        chdir($oldDir);
        $user->setPhoto('');
        $this->em->persist($user);
        $this->em->flush();
    }
}
