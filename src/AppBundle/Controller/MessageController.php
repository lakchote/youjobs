<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Form\Type\MessageFormType;
use AppBundle\Manager\MessageManager;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 */
class MessageController extends Controller
{
    /**
     * @Route("/messages", name="messages")
     */
    public function messagesAction(TokenStorage $tokenStorage, EntityManager $em, Paginator $paginator, Request $request)
    {
        $user = $tokenStorage->getToken()->getUser();
        $sortMessages = $em->getRepository('AppBundle:Message')->findBy(['user' => $user]);
        $messages = $paginator->paginate($sortMessages, $request->query->getInt('page', 1), 5);
        return $this->render('default/messages.html.twig', [
            'user' => $user,
            'messages' => $messages
        ]);
    }


    /**
     * @Route("/message/envoi/user/{slug}/{message}", name="message_envoi", defaults={"message" : "newMessage"})
     */
    public function messageEnvoiAction(User $id, Request $request, MessageManager $messageManager, Router $router, EntityManager $em, $message)
    {
        if(!$request->isXmlHttpRequest()) return new Response('Type de requête invalide', 400);
        $form = $this->createForm(MessageFormType::class);
        $form->handleRequest($request);
        if($form->isValid()) {
            if($message == 'newMessage') {
                $messageManager->sendMessage($id, $form->getData());
            } else {
                $message = $em->getRepository('AppBundle:Message')->find($message);
                $messageManager->answerMessage($message, $id, $form->getData());
            }
            $this->addFlash('success', 'Votre message à été envoyé.');
            return ($message == 'newMessage') ? (new Response())->setContent($router->generate('profil_user', ['slug' => $id->getSlug()]), 200) :
            (new Response())->setContent($router->generate('messages'), 200);
        } else {
            return (new Response())
                ->setStatusCode(400)
                ->setContent($this->renderView('modal/send_message.html.twig', [
                    'form' => $form->createView(),
                    'user' => $id
                ]));
        }
    }

    /**
     * @Route("/message/delete/{id}", name="message_delete")
     */
    public function messageDeleteAction(Message $id, MessageManager $messageManager)
    {
        $messageManager->deleteMessage($id);
        $this->addFlash('success', 'Le message a été supprimé.');
        return $this->redirectToRoute('messages');
    }

    /**
     * @Route("/message/view/{id}", name="message_view")
     */
    public function messageViewAction(Message $id)
    {
        return $this->render('default/message_view.html.twig', [
            'message' => $id
        ]);
    }
}
