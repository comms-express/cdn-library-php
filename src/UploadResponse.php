<?php

namespace CommsExpress\CDN;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class UploadResponse
{
    private $file;

    private $success;

    private $message;

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

    public function setMessage($message){
        $this->message = $message;
    }

    public function getMessage(){
        return $this->message;
    }

    public function setError(\Exception $exception){
        if($exception instanceof RequestException){
            $this->setMessage('A networking error occurred: '.$exception->getMessage());
        }elseif($exception instanceof ClientException){
            $this->setMessage('An invalid request was sent: '.$exception->getMessage());
        }else{
            $this->setMessage('A general error occurred: '.$exception->getMessage());
        }

        $this->setToFailed();
    }
}
