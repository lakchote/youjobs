<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="commentaires")
 * @Gedmo\Tree
 */
class Commentaires
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="commentaires")
     */
    private $userCommentaires;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Astuce", inversedBy="commentaires")
     */
    private $astuceCommentaires;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Le commentaire ne peut être vide.")
     * @Assert\Length(min=3, minMessage="Le commentaire doit faire au moins 3 caractères.")
     */
    private $contenu;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isFlagged = false;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Commentaires", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Commentaires")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Commentaires", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

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
    public function getUserCommentaires()
    {
        return $this->userCommentaires;
    }

    /**
     * @param mixed $userCommentaires
     */
    public function setUserCommentaires($userCommentaires)
    {
        $this->userCommentaires = $userCommentaires;
    }

    /**
     * @return mixed
     */
    public function getAstuceCommentaires()
    {
        return $this->astuceCommentaires;
    }

    /**
     * @param mixed $astuceCommentaires
     */
    public function setAstuceCommentaires($astuceCommentaires)
    {
        $this->astuceCommentaires = $astuceCommentaires;
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
    public function getFlagged()
    {
        return $this->isFlagged;
    }

    /**
     * @param mixed $isFlagged
     */
    public function setFlagged($isFlagged)
    {
        $this->isFlagged = $isFlagged;
    }

    /**
     * @return mixed
     */
    public function getCountIsFlagged()
    {
        return $this->countIsFlagged;
    }

    public function setCountIsFlagged()
    {
        $this->countIsFlagged++;
    }

    public function resetCountIsFlagged()
    {
        $this->countIsFlagged = 0;
    }

    /**
     * @ORM\Column(type="integer")
     */
    private $countIsFlagged = 0;

    /**
     * @return mixed
     */
    public function getisFlagged()
    {
        return $this->isFlagged;
    }

    /**
     * @param mixed $isFlagged
     */
    public function setIsFlagged($isFlagged)
    {
        $this->isFlagged = $isFlagged;
    }

    /**
     * @return mixed
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * @param mixed $lft
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    }

    /**
     * @return mixed
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * @param mixed $rgt
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    }

    /**
     * @return mixed
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * @param mixed $lvl
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Commentaires $parent
     */
    public function setParent(Commentaires $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param mixed $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }
}
