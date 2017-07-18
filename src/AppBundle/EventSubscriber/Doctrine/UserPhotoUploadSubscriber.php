<?php


namespace AppBundle\EventSubscriber\Doctrine;


use AppBundle\Entity\User;
use AppBundle\Service\FileUploader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\File\File;

class UserPhotoUploadSubscriber implements EventSubscriber
{
    private $fileUploader;
    private $directory;

    public function __construct(FileUploader $fileUploader, $directory)
    {
        $this->fileUploader = $fileUploader;
        $this->directory = $directory;
    }

    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate', 'postLoad'];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if(!$entity instanceof User) return;
        $filename = $entity->getPhoto();
        if(!empty($filename)) $entity->setPhoto(new File($this->directory . '/' . $filename));
    }

    private function uploadFile($entity)
    {
        if(!$entity instanceof User) return;
        $file = $entity->getPhoto();
        if(!$file instanceof File) return;
        $filename = $this->fileUploader->upload($file);
        $entity->setPhoto($filename);
    }
}
