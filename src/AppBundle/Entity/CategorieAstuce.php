<?php


namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="categorie_astuce")
 */
class CategorieAstuce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $titre;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Astuce", mappedBy="categorieAstuce")
     */
    private $astuces;

    public function __construct()
    {
        $this->astuces = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getAstuces()
    {
        return $this->astuces;
    }

    /**
     * @param mixed $astuce
     */
    public function setAstuces(Astuce $astuce)
    {
        $this->astuces[] = $astuce;
    }
}
