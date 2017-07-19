<?php

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="L'adresse mail est déjà utilisée.")
 */
class User implements UserInterface, \Serializable
{
    private $imgPath = '/uploads/user/';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    private $plainPassword;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $resetPassword;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez indiquer votre nom.")
     * @Assert\Length(max=50, maxMessage="Le nom est trop grand.")
     */
    private $nom;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez indiquer votre prénom.")
     * @Assert\Length(max=50, maxMessage="Le prénom est trop grand.", min=3, minMessage="Le prénom doit faire au moins 3 caractères.")
     */
    private $prenom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInscription;

    /**
     * @ORM\Column(type="string")
     * @Assert\Email(message="L'email n'est pas valide.", checkMX=true)
     * @Assert\NotBlank(message="Vous devez indiquer votre e-mail.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(max=400, maxMessage="La description est trop volumineuse.", min=50, minMessage="La description est trop petite.")
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(mimeTypes={"image/jpeg", "image/png"})
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="user", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Annonce", mappedBy="userAnnonce", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $annonces;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Astuce", mappedBy="userAstuce", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $astuces;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbRemerciements;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $statut;

    const STATUT1 = 'Nouveau venu';

    const STATUT2 = 'Contributeur occasionnel';

    const STATUT3 = 'Contributeur';

    const STATUT4 = 'Super contributeur';

    const STATUT5 = 'Le partage c\'est la vie';

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->annonces = new ArrayCollection();
        $this->astuces = new ArrayCollection();
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->nom,
            $this->prenom,
            $this->email,
            $this->password
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->nom,
            $this->prenom,
            $this->email,
            $this->password
            ) = unserialize($serialized);
    }

    public function getRoles()
    {
        $roles = $this->roles;
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }
        return $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getImgPath()
    {
        return $this->imgPath;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    /**
     * @return mixed
     */
    public function getResetPassword()
    {
        return $this->resetPassword;
    }

    /**
     * @param mixed $resetPassword
     */
    public function setResetPassword($resetPassword)
    {
        $this->resetPassword = $resetPassword;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    /**
     * @param mixed $dateInscription
     */
    public function setDateInscription($dateInscription)
    {
        $this->dateInscription = $dateInscription;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return (empty($this->description)) ? 'Renseignez votre description pour vous faire connaître !' : $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        if($photo !== null) $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param mixed $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }

    /**
     * @return mixed
     */
    public function getAnnonces()
    {
        return $this->annonces;
    }

    /**
     * @param mixed $annonces
     */
    public function setAnnonces($annonces)
    {
        $this->annonces = $annonces;
    }

    /**
     * @return mixed
     */
    public function getAstuces()
    {
        return $this->astuces;
    }

    /**
     * @param mixed $astuces
     */
    public function setAstuces($astuces)
    {
        $this->astuces = $astuces;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles[] = $roles;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getNbRemerciements()
    {
        return (empty($this->nbRemerciements)) ? 0 : $this->nbRemerciements;
    }

    /**
     * @param mixed $nbRemerciements
     */
    public function setNbRemerciements($nbRemerciements)
    {
        $this->nbRemerciements = $nbRemerciements;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        switch($this->nbRemerciements) {
            case $this->nbRemerciements <= 50 :
                return self::STATUT1;
            case $this->nbRemerciements <= 100 :
                return self::STATUT2;
            case $this->nbRemerciements <= 150 :
                return self::STATUT3;
            case $this->nbRemerciements <= 200 :
                return self::STATUT4;
            case $this->nbRemerciements <= 250 :
                return self::STATUT5;
        }
        if(empty($this->nbRemerciements)) return self::STATUT1;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

}
