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
        $message = \Swift_Message::newInstance()
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

    public function sendResetPasswordMail($data)
    {
        $resetPassword = md5(uniqid());
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['email' => $data['email']]);
        $user->setResetPassword($resetPassword);
        $this->em->persist($user);
        $this->em->flush();
        $message = \Swift_Message::newInstance()
            ->setSubject('Réinitialisation du mot de passe YouJobs')
            ->setFrom('noreply@youjobs.com')
            ->setTo($data['email'])
            ->setBody($this->twig->render('mail/reset_password.html.twig', [
                'resetPassword' => $resetPassword
            ]), 'text/html');
        $this->mailer->send($message);
    }
}
