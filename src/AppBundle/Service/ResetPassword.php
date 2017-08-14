<?php


namespace AppBundle\Service;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class ResetPassword
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function changePassword(User $user, $data)
    {
        if($user->getResetPassword() !== $data['resetPassword']) return false;
        $user->setPlainPassword($data['plainPassword']);
        $user->setResetPassword(null);
        $this->em->persist($user);
        $this->em->flush();
        return true;
    }
}
