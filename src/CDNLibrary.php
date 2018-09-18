<?php

namespace CommsExpress\CDN;

use CommsExpress\CDN\Exceptions\URLNotSet;
use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\stream_for;
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
                'multipart'  =>  [
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

    public function getFile($file_id){
        try{
            $client_response = $this->client->get('api/file/'.$file_id);

            $file = $this->buildFileObjectFromResponseBody(json_decode($client_response->getBody()));
        }catch(Exception $e){
            return null;
        }

        return $file;
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