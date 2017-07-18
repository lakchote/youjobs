<?php


namespace AppBundle\Service;


use Symfony\Component\HttpFoundation\File\File;

class FileUploader
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function upload(File $file)
    {
        $filename = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($this->directory, $filename);
        return $filename;
    }
}
