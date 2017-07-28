<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="annonce")
 */
class Annonce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="annonces", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(max=4000, maxMessage="L'annonce est trop volumineuse.", min=600, minMessage="L'annonce est trop petite.")
     * @Assert\NotBlank(message="L'annonce ne peut être vide.")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePublication;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Categorie", inversedBy="annonces")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TypeAnnonce", inversedBy="annonces")
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $annonceSignalee;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbSignalements;


    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $userAnnonce
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * @param mixed $contenu
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }

    /**
     * @return mixed
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * @param mixed $datePublication
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;
    }

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function getAnnonceSignalee()
    {
        return $this->annonceSignalee;
    }

    /**
     * @param boolean $annonceSignalee
     */
    public function setAnnonceSignalee($annonceSignalee)
    {
        $this->annonceSignalee = $annonceSignalee;
    }

    /**
     * @return integer
     */
    public function getNbSignalements()
    {
        return $this->nbSignalements;
    }

    public function setNbSignalements()
    {
        $this->nbSignalements++;
    }

    public function removeSignalement()
    {
        if($this->nbSignalements-- > 0) $this->nbSignalements--;
    }

}
