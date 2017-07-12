<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="astuces")
     */
    private $userAstuce;

    /**
     * @ORM\Column(type="string")
     * @Assert\Length(max=4000, maxMessage="L'astuce est trop volumineuse.", min=50, minMessage="L'astuce est trop petite.")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePublication;

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

}
