<?php

namespace CommsExpress\CDN;


class File
{
    private $id;
    private $url;

    public function getID(){
        return $this->id;
    }

    public function getURL(){
        return $this->url;
    }

    public function setID($id){
        $this->id = $id;
    }

    public function setURL($url){
        $this->url = $url;
    }
}