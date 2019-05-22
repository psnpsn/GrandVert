<?php
/**
 * Created by PhpStorm.
 * User: Bouazza Med
 * Date: 19/02/2019
 * Time: 21:21
 */

namespace EvenementBundle;
use Symfony\Component\HttpFoundation\File\UploadedFile;



class ImageUpload
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDir(), $fileName);

        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}