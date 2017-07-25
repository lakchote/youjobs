<?php


namespace AppBundle\Entity;


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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="astuces", cascade={"persist", "remove"})
     */
    private $userAstuce;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(max=1000, maxMessage="L'astuce est trop volumineuse.", min=200, minMessage="L'astuce est trop petite.")
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $astuceSignalee = false;

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

}
