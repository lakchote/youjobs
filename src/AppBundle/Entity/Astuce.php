<?php


namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AstuceRepository")
 * @ORM\Table(name="astuce")
 */
class Astuce
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="astuces", cascade={"persist"})
     */
    private $userAstuce;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="astucesFavorites", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $usersAstucesFavorites;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CategorieAstuce", inversedBy="astuces")
     */
    private $categorieAstuce;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Commentaires", mappedBy="astuceCommentaires", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $commentaires;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(max=1500, maxMessage="L'astuce est trop volumineuse.", min=200, minMessage="L'astuce est trop petite.")
     * @Assert\NotBlank(message="L'astuce ne peut être vide.")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePublication;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbRemerciements = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbSignalements;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $astuceSignalee = false;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez renseigner le sujet de l'astuce.")
     * @Assert\Length(min=11, minMessage="L'intitulé de l'astuce est trop court.")
     */
    private $intituleAstuce;

    public function __construct()
    {
        $this->usersAstucesFavorites = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getUserAstuce()
    {
        return $this->userAstuce;
    }

    /**
     * @param mixed $userAstuce
     */
    public function setUserAstuce($userAstuce)
    {
        $this->userAstuce = $userAstuce;
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return integer
     */
    public function getNbRemerciements()
    {
        return $this->nbRemerciements;
    }

    public function setNbRemerciements()
    {
        $this->nbRemerciements++;
    }

    public function removeRemerciement()
    {
        if($this->nbRemerciements > 0) $this->nbRemerciements--;
    }

    /**
     * @return boolean
     */
    public function getAstuceSignalee()
    {
        return $this->astuceSignalee;
    }

    /**
     * @param boolean $astuceSignalee
     */
    public function setAstuceSignalee($astuceSignalee)
    {
        $this->astuceSignalee = $astuceSignalee;
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
        if($this->nbSignalements > 0) $this->nbSignalements--;
    }

    /**
     * @return mixed
     */
    public function getUsersAstucesFavorites()
    {
        return $this->usersAstucesFavorites;
    }

    /**
     * @param mixed $usersAstucesFavorites
     */
    public function setUsersAstucesFavorites($userAstucesFavorites)
    {
        $this->usersAstucesFavorites[] = $userAstucesFavorites;
    }

    /**
     * @return string
     */
    public function getIntituleAstuce()
    {
        return $this->intituleAstuce;
    }

    /**
     * @param string $intituleAstuce
     */
    public function setIntituleAstuce($intituleAstuce)
    {
        $this->intituleAstuce = $intituleAstuce;
    }

    /**
     * @return mixed
     */
    public function getCategorieAstuce()
    {
        return $this->categorieAstuce;
    }

    /**
     * @param mixed $typeAstuce
     */
    public function setCategorieAstuce($categorieAstuce)
    {
        $this->categorieAstuce = $categorieAstuce;
    }

    /**
     * @return mixed
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * @param mixed $commentaire
     */
    public function setCommentaires($commentaire)
    {
        $this->commentaires[] = $commentaire;
    }

}
