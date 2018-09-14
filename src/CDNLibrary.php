<?php

namespace CommsExpress\CDN;

use CommsExpress\CDN\Exceptions\URLNotSet;
use GuzzleHttp\Client;
use PHPUnit\Framework\Exception;

class CDNLibrary
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;

        $this->checkClientHasURL();
    }

    public function upload($file): UploadResponse{
        $response = new UploadResponse();

        try{
            $client_response = $this->client->post('api/upload', [
                'multipart' =>  [
                    [
                        'name'  =>  'file',
                        'contents'  =>  $file
                    ]
                ],
                'headers'   =>  [
                    'Accept'    =>  'application/json'
                ]
            ]);

            $file = $this->buildFileObjectFromResponseBody(json_decode($client_response->getBody()));
            $response->setToSuccessful();
            $response->addFile($file);

        }catch(\Exception $e){
            $response->setToFailed();
        }

        return $response;
    }

    private function buildFileObjectFromResponseBody($responseBody): File{
        $file = new File();
        $file->setID($responseBody->data->id);
        $file->setURL($responseBody->data->attributes->url);
        return $file;
    }

    private function checkClientHasURL(){
        if(!$this->client->getConfig('base_uri')){
            throw new URLNotSet();
        }
    }
}