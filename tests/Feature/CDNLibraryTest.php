<?php

namespace Tests\Feature;

use CommsExpress\CDN\CDNLibrary;
use CommsExpress\CDN\File;
use CommsExpress\CDN\UploadResponse;

class CDNLibraryTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    
    public function uploads_a_file()
    {
        $mock = new \GuzzleHttp\Handler\MockHandler([
            new \GuzzleHttp\Psr7\Response(200,[],$this->prepareResponse())
        ]);
        $handler = \GuzzleHttp\HandlerStack::create($mock);

        $client = new \GuzzleHttp\Client([
            'handler'   =>  $handler,
            'base_uri'  =>  'http://cdn.test'
        ]);

        $library = new CDNLibrary($client);

        $response = $library->upload($this->getFile());

        $this->assertInstanceOf(UploadResponse::class, $response);
        $this->assertTrue($response->success());
        $this->assertInstanceOf(File::class, $response->getFile());

        $file = $response->getFile();
        $this->assertEquals($file->getID(), 1);
        $this->assertEquals($file->getURL(), 'http://cdn.test/file.png');
    }

    private function getFile(){
        return file_get_contents('./tests/example.png');
    }

    private function prepareResponse(){
        return json_encode([
            'data'    =>  [
                'id'    =>  1,
                'attributes'    =>  [
                    'url'   =>  'http://cdn.test/file.png'
                ]
            ]
        ]);
    }
}