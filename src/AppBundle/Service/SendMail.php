<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class SendMail
{
    private $mailer;
    private $twig;
    private $em;
    private $contactMail;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig,  EntityManager $em, $contactMail)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->em = $em;
        $this->contactMail = $contactMail;
    }

    public function sendContactMail($data)
    {
        $message = new \Swift_Message();
        $message
            ->setSubject($data['sujet'])
            ->setFrom($data['email'])
            ->setTo($this->contactMail)
            ->setBody($this->twig->render('mail/contact_mail.html.twig', [
                'sujet' => $data['sujet'],
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'message' => $data['message']
            ]), 'text/html');
        $this->mailer->send($message);
    }
}
