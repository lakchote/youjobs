<?php

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, groups={"registration"}, message="L'adresse mail est déjà utilisée.")
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
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(max=1000, maxMessage="La description est trop volumineuse.", min=50, minMessage="La description est trop petite.")
     */
    private $contenu;

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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="auteurMessage", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $expediteurMessages;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Annonce", mappedBy="user", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $annonces;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Astuce", mappedBy="userAstuce", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $astuces;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Astuce", mappedBy="usersAstucesFavorites", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $astucesFavorites;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Commentaires", mappedBy="userCommentaires", cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $commentaires;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbRemerciements;

    /**
     * @ORM\Column(type="json_array")
     */
    private $signalementsAnnonces = [];

    /**
     * @ORM\Column(type="json_array")
     */
    private $remerciementsAnnonces = [];

    /**
     * @ORM\Column(type="json_array")
     */
    private $remerciementsAstuces = [];

    /**
     * @ORM\Column(type="json_array")
     */
    private $signalementsAstuces = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $messageAstucesLu = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $messageAnnoncesLu = false;

    /**
     * @Gedmo\Slug(fields={"nom", "prenom"})
     * @ORM\Column(type="string")
     */
    private $slug;

    const STATUT1 = 'Nouveau venu';

    const STATUT2 = 'Contributeur occasionnel';

    const STATUT3 = 'Contributeur';

    const STATUT4 = 'Super contributeur';

    const STATUT5 = 'Le partage c\'est la vie';

    const DESCRIPTION = 'Renseignez votre description pour vous faire connaître !';

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->annonces = new ArrayCollection();
        $this->astuces = new ArrayCollection();
        $this->astucesFavorites = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->expediteurMessages = new ArrayCollection();
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
    public function getContenu()
    {
        return (empty($this->contenu)) ? self::DESCRIPTION : $this->contenu;
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
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        if ($photo !== null) $this->photo = $photo;
    }

    public function erasePhoto()
    {
        $this->photo = null;
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
        $this->messages[] = $messages;
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
        $this->annonces[] = $annonces;
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
        $this->astuces[] = $astuces;
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

    public function setNbRemerciements()
    {
        $this->nbRemerciements++;
    }

    public function removeRemerciement()
    {
        if($this->nbRemerciements > 0) $this->nbRemerciements--;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        switch ($this->nbRemerciements) {
            case $this->nbRemerciements <= 5 :
                return self::STATUT1;
            case $this->nbRemerciements <= 10 :
                return self::STATUT2;
            case $this->nbRemerciements <= 15 :
                return self::STATUT3;
            case $this->nbRemerciements < 30 :
                return self::STATUT4;
        }
        return (empty($this->nbRemerciements)) ? self::STATUT1 : self::STATUT5;
    }

    /**
     * @return mixed
     */
    public function getSignalementsAnnonces()
    {
        return $this->signalementsAnnonces;
    }

    public function setSignalementsAnnonces($id)
    {
        $this->signalementsAnnonces[] = $id;
    }

    public function updateSignalementsAnnonces($signalementAnnonceId)
    {
        unset($this->signalementsAnnonces[$signalementAnnonceId]);
    }

    /**
     * @return mixed
     */
    public function getRemerciementsAnnonces()
    {
        return $this->remerciementsAnnonces;
    }

    public function setRemerciementsAnnonces($id)
    {
        $this->remerciementsAnnonces[] = $id;
    }

    public function updateRemerciementsAnnonces($annonceIdToRemove)
    {
        unset($this->remerciementsAnnonces[$annonceIdToRemove]);
    }

    /**
     * @return mixed
     */
    public function getRemerciementsAstuces()
    {
        return $this->remerciementsAstuces;
    }

    /**
     * @param mixed $remerciementsAstuces
     */
    public function setRemerciementsAstuces($remerciementsAstuces)
    {
        $this->remerciementsAstuces[] = $remerciementsAstuces;
    }

    public function updateRemerciementsAstuces($astuceIdToRemove)
    {
        unset($this->remerciementsAstuces[$astuceIdToRemove]);
    }

    /**
     * @return mixed
     */
    public function getSignalementsAstuces()
    {
        return $this->signalementsAstuces;
    }

    /**
     * @param mixed $signalementsAstuces
     */
    public function setSignalementsAstuces($signalementsAstuces)
    {
        $this->signalementsAstuces[] = $signalementsAstuces;
    }

    public function updateSignalementsAstuces($astuceIdToRemove)
    {
        unset($this->signalementsAstuces[$astuceIdToRemove]);
    }

    /**
     * @return boolean
     */
    public function getMessageAstucesLu()
    {
        return $this->messageAstucesLu;
    }

    /**
     * @param boolean $messageAstucesLu
     */
    public function setMessageAstucesLu($messageAstucesLu)
    {
        $this->messageAstucesLu = $messageAstucesLu;
    }

    /**
     * @return boolean
     */
    public function getMessageAnnoncesLu()
    {
        return $this->messageAnnoncesLu;
    }

    /**
     * @param boolean $messageAnnoncesLu
     */
    public function setMessageAnnoncesLu($messageAnnoncesLu)
    {
        $this->messageAnnoncesLu = $messageAnnoncesLu;
    }

    /**
     * @return mixed
     */
    public function getAstucesFavorites()
    {
        return $this->astucesFavorites;
    }

    /**
     * @param mixed $astucesFavorites
     */
    public function setAstucesFavorites($astucesFavorites)
    {
        $this->astucesFavorites[] = $astucesFavorites;
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

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function getExpediteurMessages()
    {
        return $this->expediteurMessages;
    }

    /**
     * @param Message $message
     */
    public function setExpediteurMessages(Message $message)
    {
        $this->expediteurMessages[] = $message;
    }

}
