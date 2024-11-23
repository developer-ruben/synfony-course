<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

    class FileUploader{
        private $projectDir;

        public function __construct(string $projectDir)
        {
            $this->projectDir = $projectDir;
        }

        public function uploadFile(UploadedFile $file){
            $fileName = md5(uniqid()).'.'.$file->guessClientExtension();
            $file->move($this->projectDir.'/public/uploads' , $fileName);

            return $fileName;
        }

        public function removeFile(string $fileName){
            unlink($this->getFilePath($fileName));
        }

        public function getFilePath($fileName)
        {
            return $this->projectDir.'/public/uploads/' . $fileName;
        }
    }