<?php


namespace AppBundle\EventSubscriber\Form;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SanitizeUserInput implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [FormEvents::PRE_SUBMIT => 'sanitizeNewLines'];
    }

    public function sanitizeNewLines(FormEvent $event)
    {
        $formData = $event->getData();
        $trimmedData = trim($formData['contenu']);
        $formData['contenu'] =  preg_replace("/(\r?\n){2,}/", "\r\n\r\n", $trimmedData);
        $event->setData($formData);
     }
}
