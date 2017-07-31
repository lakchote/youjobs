<?php


namespace AppBundle\Repository;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class AstuceRepository extends EntityRepository
{
    public function getBetterRankedTips()
    {
        return $this
            ->createQueryBuilder('tips')
            ->orderBy('tips.nbRemerciements', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getAstucesForAUser(User $id)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.userAstuce = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
