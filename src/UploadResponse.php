<?php

namespace CommsExpress\CDN;


class UploadResponse
{
    private $file;

    private $success;

    public function success(){
        return $this->success;
    }

    public function failed(){
        return !$this->success;
    }

    public function setToSuccessful(){
        $this->success = true;
    }

    public function setToFailed(){
        $this->success = false;
    }

    public function addFile(File $file){
        $this->file = $file;
    }

    public function getFile(){
        return $this->file;
    }
}