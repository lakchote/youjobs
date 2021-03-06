<?php


namespace AppBundle\Repository;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class AnnonceRepository extends EntityRepository
{
    public function countAnnoncesForACategorie($titreCategorie)
    {
        return $this
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->leftJoin('a.categorie', 'c')
            ->andWhere('c.titre = :titreCategorie')
            ->setParameter('titreCategorie', $titreCategorie)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAnnoncesForACategorie($titreCategorie)
    {
        return $this
            ->createQueryBuilder('a')
            ->orderBy('a.datePublication', 'DESC')
            ->leftJoin('a.categorie', 'c')
            ->andWhere('c.titre = :titreCategorie')
            ->setParameter('titreCategorie', $titreCategorie)
            ->getQuery()
            ->getResult();
    }

    public function countAnnoncesForACategorieAndAUser($titreCategorie, User $id)
    {
        return $this
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.user = :id')
            ->leftJoin('a.categorie', 'c')
            ->andWhere('c.titre = :titreCategorie')
            ->setParameter('id', $id)
            ->setParameter('titreCategorie', $titreCategorie)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAnnoncesForACategorieAndAUser($titreCategorie, User $id)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.user = :id')
            ->orderBy('a.datePublication', 'DESC')
            ->leftJoin('a.categorie', 'c')
            ->andWhere('c.titre = :titreCategorie')
            ->setParameter('id', $id)
            ->setParameter('titreCategorie', $titreCategorie)
            ->getQuery()
            ->getResult();
    }

    public function countSearchResultsForTerm($searchTerm)
    {
        return $this
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->andWhere('a.intitulePoste LIKE :searchTerm')
            ->setParameter('searchTerm', $searchTerm . '%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getSearchResultsForTerm($searchTerm)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.intitulePoste LIKE :searchTerm')
            ->setParameter('searchTerm', $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }

    public function countAnnoncesSignalees()
    {
        return $this
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.annonceSignalee = true')
            ->getQuery()
            ->getSingleScalarResult();
    }

}
