<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 * @ORM\Table(name="message")
 */
class Message
{
    const MESSAGE_READ = 'READ';
    const MESSAGE_UNREAD = 'UNREAD';
    const MESSAGE_ANSWERED = 'ANSWERED';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="messages")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="expediteurMessages")
     */
    private $auteurMessage;

    /**
     * @ORM\Column(type="string")
     * @Assert\Length(max=2000, maxMessage="Le message est trop volumineux pour être envoyé.", min=3, minMessage="Le message doit faire au moins 3 caractères.")
     * @Assert\NotBlank(message="Le message ne peut être vide.")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnvoi;

    /**
     * @ORM\Column(type="string")
     */
    private $status = self::MESSAGE_UNREAD;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
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
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * @param mixed $dateEnvoi
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getAuteurMessage()
    {
        return $this->auteurMessage;
    }

    /**
     * @param User $auteurMessage
     */
    public function setAuteurMessage(User $auteurMessage)
    {
        $this->auteurMessage = $auteurMessage;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

}
