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

    public function getAstucesFavoritesForCurrentUser(User $id)
    {
        return $this
            ->createQueryBuilder('a')
            ->leftJoin('a.usersAstucesFavorites', 'favoris')
            ->where('favoris.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function countAstucesForACategorie($titreCategorie)
    {
        return $this
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->leftJoin('a.categorieAstuce', 'c')
            ->andWhere('c.titre = :titreCategorie')
            ->setParameter('titreCategorie', $titreCategorie)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countAstucesForACategorieAndAUser($titreCategorie, User $id)
    {
        return $this
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.userAstuce = :id')
            ->leftJoin('a.categorieAstuce', 'c')
            ->andWhere('c.titre = :titreCategorie')
            ->setParameter('id', $id)
            ->setParameter('titreCategorie', $titreCategorie)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAstucesForACategorieAndAUser($titreCategorie, User $id)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.userAstuce = :id')
            ->orderBy('a.datePublication', 'DESC')
            ->leftJoin('a.categorieAstuce', 'c')
            ->andWhere('c.titre = :titreCategorie')
            ->setParameter('id', $id)
            ->setParameter('titreCategorie', $titreCategorie)
            ->getQuery()
            ->getResult();
    }

    public function getAstucesForACategorie($titreCategorie)
    {
        return $this
            ->createQueryBuilder('a')
            ->orderBy('a.datePublication', 'DESC')
            ->leftJoin('a.categorieAstuce', 'c')
            ->andWhere('c.titre = :titreCategorie')
            ->setParameter('titreCategorie', $titreCategorie)
            ->getQuery()
            ->getResult();
    }
}
